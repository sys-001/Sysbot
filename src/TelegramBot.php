<?php

namespace TelegramBot;

use GuzzleHttp\Client;
use TelegramBot\{Event\DefaultEvent,
    Event\EventHandler,
    Event\Trigger,
    Exception\SettingsProviderException,
    Exception\TelegramBotException,
    Telegram\Methods,
    Telegram\Types,
    Telegram\Types\Response,
    Telegram\Types\Update};


/**
 * Class TelegramBot
 * @package TelegramBot
 */
class TelegramBot
{

    /**
     *
     */
    private const ENDPOINT = "https://api.telegram.org/";

    /**
     * @var null|string
     */
    private $token = null;

    /**
     * @var Client|null
     */
    private $client = null;

    /**
     * @var null|SettingsProvider
     */
    private $provider = null;

    /**
     * @var null|Settings\Settings
     */
    private $settings = null;

    /**
     * @var mixed|null
     */
    private $update = null;

    /**
     * @var bool
     */
    private $use_polling = false;


    /**
     * @var null|Logger
     */
    private $logger = null;


    /**
     * @var null|ExceptionHandler
     */
    private $exception_handler = null;

    /**
     * @var null|EventHandler
     */
    private $event_handler = null;
    /**
     * @var null
     */
    private $results_callback = null;


    /**
     * TelegramBot constructor.
     * @param string $token
     * @param string $settings_path
     * @param int $log_verbosity
     * @param string|null $log_path
     * @throws SettingsProviderException
     * @throws TelegramBotException
     * @throws \Exception
     */
    function __construct(string $token, string $settings_path = "config/settings.json", int $log_verbosity = 0, string $log_path = null)
    {
        $this->logger = new Logger($log_verbosity, $log_path);
        $this->exception_handler = new ExceptionHandler($this->logger);
        $this->token = str_replace("bot", "", $token);
        $this->provider = new SettingsProvider($this->logger, $settings_path);
        $this->event_handler = new EventHandler();
        if (file_exists($settings_path)) {
            $this->settings = $this->provider->loadSettings()->getSettings();
        } else {
            $this->settings = $this->provider->createSettings()->getSettings();
            $this->provider->saveSettings();
        }
        if ($this->logger->getVerbosity() >= 1) {
            $log_message = sprintf("TelegramBot: New TelegramBot instance created (Token: '%s', Settings path: '%s', Logger verbosity: '%d', Logger path: '%s')",
                $this->token, $settings_path, $log_verbosity, $log_path);
            $this->logger->log($log_message);
        }
        if ($this->settings->getTelegramSection()->getUseTestApi()) {
            $this->token = sprintf("%s/test", $this->token);
        }
        $this->client = new Client(['base_uri' => sprintf("%sbot%s/", self::ENDPOINT, $this->token), 'timeout' => 30]);
        if (!$this->getMe()->ok) {
            throw new TelegramBotException("Invalid token provided");
        }
        $raw_update = file_get_contents("php://input");
        if (empty($this->getWebhookInfo()->result->url) and empty($raw_update)) {
            $this->use_polling = true;
        } else {
            $this->update = Update::parseUpdate(json_decode($raw_update));
        }
        if ($this->settings->getGeneralSection()->getCheckIp() and !$this->use_polling) {
            $this->checkRequest();
        }
    }


    /**
     * @return null|Response
     */
    function getMe(): ?Response
    {
        return $this->sendRequest(new Methods\GetMe());
    }


    /**
     * @param Methods\MethodInterface $method
     * @return null|Response
     */
    function sendRequest(Methods\MethodInterface $method): ?Response
    {
        $method_name = $method->getMethodName();
        $params = $method->getParams();
        $params = array_filter($params, function ($value) {
            return null != $value;
        });
        if (isset($params['reply_markup'])) $params['reply_markup'] = json_encode($params['reply_markup']);
        if ($method->isMultipart()) {
            $multipart_params = [];
            foreach ($params as $param => $value) {
                $multipart_params[] = [
                    'name' => $param,
                    'contents' => $value
                ];
            }
            $response = $this->client->post($method_name, [
                'multipart' => $multipart_params,
                'headers' => ['Connection' => 'Keep-Alive', 'Keep-Alive' => '120'],
                'http_errors' => false
            ]);
        } else {
            $response = $this->client->post($method_name, [
                'form_params' => $params,
                'headers' => ['Connection' => 'Keep-Alive', 'Keep-Alive' => '120'],
                'http_errors' => false
            ]);
        }
        $raw_result = $response->getBody();
        $result = json_decode($raw_result);
        if ($this->logger->getVerbosity() === 2 and $method_name != "getUpdates") {
            $log_message = sprintf("TelegramBot: New Response received from request '%s' with params '%s'. (%s)",
                $method_name, json_encode($params, JSON_UNESCAPED_SLASHES), $raw_result);
            $this->logger->log($log_message);
        }
        $result_params = $method->getResultParams();
        $result_name = $result_params['type'];
        if (!empty($result_name)) {
            $method_name = sprintf('parse%s', $result_name);
            if ($result_params['multiple']) $method_name = sprintf('%ss', $method_name);
            $result_type = (object)[
                'class' => $result_name,
                'method' => $method_name
            ];
            $result->result_type = $result_type;
        }
        return Response::parseResponse($result);
    }


    /**
     * @return null|Response
     */
    function getWebhookInfo(): ?Response
    {
        return $this->sendRequest(new Methods\GetWebhookInfo());
    }


    /**
     * @throws TelegramBotException
     */
    private function checkRequest(): void
    {
        $is_logging = $this->logger->getVerbosity() >= 1 ? true : false;
        if (isset($_GET["administration"]) and password_verify($_GET["administration"], $this->settings->getGeneralSection()->getAdministrationPassword())) {
            $this->logger->log("TelegramBot: Administration password verified; request check ignored.");
            return;
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            throw new TelegramBotException("Could not get IP Address");
        }
        if ($is_logging) {
            $log_message = sprintf("TelegramBot: Checking request made from IP Address '%s'...", $ip);
            $this->logger->log($log_message);
        }
        $allowed_addresses = [];
        for ($i = 197; $i <= 233; $i++) {
            $allowed_addresses[] = "149.154.167.$i";
        }
        if (!in_array($ip, $allowed_addresses)) {
            throw new TelegramBotException("Unauthorized");
        }
        if ($is_logging) {
            $log_message = sprintf("TelegramBot: Request from IP Address '%s' is valid.", $ip);
            $this->logger->log($log_message);
        }
        return;
    }


    /**
     * @return null|SettingsProvider
     */
    public function getProvider(): ?SettingsProvider
    {
        return $this->provider;
    }


    /**
     *
     */
    function refreshSettings(): void
    {
        $this->settings = $this->provider->getSettings();
        return;
    }


    /**
     * @param string $url
     * @param array $params
     * @return null|Response
     */
    function setWebhook(string $url, array $params = []): ?Response
    {
        $method = new Methods\SetWebhook(
            $url,
            $params['certificate'] ?? null,
            $params['max_connections'] ?? 40,
            $params['allowed_updates'] ?? []);
        $response = $this->sendRequest($method);
        return $response;
    }


    /**
     * @return null|Response
     */
    function deleteWebhook(): ?Response
    {
        return $this->sendRequest(new Methods\DeleteWebhook());
    }

    /**
     * @param string $chat_id
     * @param string $from_chat_id
     * @param int $message_id
     * @param bool $disable_notification
     * @return null|Response
     */
    function forwardMessage(string $chat_id, string $from_chat_id, int $message_id, bool $disable_notification = false): ?Response
    {
        $method = new Methods\ForwardMessage(
            $chat_id,
            $from_chat_id,
            $message_id,
            $disable_notification
        );
        return $this->sendRequest($method);
    }


    /**
     * @param Types\InputFile $photo
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendPhoto(Types\InputFile $photo, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendPhoto(
            $chat_id,
            $photo,
            $params['caption'] ?? null,
            $params['parse_mode'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @return int
     * @throws TelegramBotException
     */
    private function getChatID(): int
    {
        if (!empty($this->update->message->chat->id)) {
            return $this->update->message->chat->id;
        } elseif (!empty($this->update->callback_query->message->chat->id)) {
            return $this->update->callback_query->message->chat->id;
        }
        throw new TelegramBotException('Unable to get Chat ID');
    }


    /**
     * @param Types\InputFile $audio
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendAudio(Types\InputFile $audio, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendAudio(
            $chat_id,
            $audio,
            $params['caption'] ?? null,
            $params['parse_mode'] ?? null,
            $params['duration'] ?? null,
            $params['performer'] ?? null,
            $params['title'] ?? null,
            $params['thumb'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param Types\InputFile $document
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendDocument(Types\InputFile $document, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendDocument(
            $chat_id,
            $document,
            $params['thumb'] ?? null,
            $params['caption'] ?? null,
            $params['parse_mode'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param Types\InputFile $video
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendVideo(Types\InputFile $video, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendVideo(
            $chat_id,
            $video,
            $params['duration'] ?? null,
            $params['width'] ?? null,
            $params['height'] ?? null,
            $params['thumb'] ?? null,
            $params['caption'] ?? null,
            $params['parse_mode'] ?? null,
            $params['supports_streaming'] ?? false,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param Types\InputFile $video_note
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendVideoNote(Types\InputFile $video_note, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendVideoNote(
            $chat_id,
            $video_note,
            $params['duration'] ?? null,
            $params['length'] ?? null,
            $params['thumb'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param Types\InputFile $voice
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendVoice(Types\InputFile $voice, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendVoice(
            $chat_id,
            $voice,
            $params['caption'] ?? null,
            $params['parse_mode'] ?? null,
            $params['duration'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param Types\InputFile $sticker
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendSticker(Types\InputFile $sticker, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendSticker(
            $chat_id,
            $sticker,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param array $media
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendMediaGroup(array $media, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SendMediaGroup(
            $chat_id,
            $media,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null
        ));
    }


    /**
     * @param float $latitude
     * @param float $longitude
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendLocation(float $latitude, float $longitude, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SendLocation(
            $chat_id,
            $latitude,
            $longitude,
            $params['live_period'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $title
     * @param string $address
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendVenue(float $latitude, float $longitude, string $title, string $address, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SendVenue(
            $chat_id,
            $latitude,
            $longitude,
            $title,
            $address,
            $params['foursquare_id'] ?? null,
            $params['foursquare_type'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param float $latitude
     * @param float $longitude
     * @param bool $is_inline
     * @param string $message_id
     * @param string|null $chat_id
     * @param string|null $reply_markup
     * @return null|Response
     * @throws TelegramBotException
     */
    function editMessageLiveLocation(float $latitude, float $longitude, bool $is_inline, string $message_id, string $chat_id = null, string $reply_markup = null): ?Response
    {
        if ($is_inline) {
            $inline_message_id = $message_id;
        } elseif (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\EditMessageLiveLocation(
            $chat_id ?? null,
            $message_id,
            $inline_message_id ?? null,
            $latitude,
            $longitude,
            $reply_markup ?? null
        ));
    }


    /**
     * @param bool $is_inline
     * @param string $message_id
     * @param string|null $chat_id
     * @param string|null $reply_markup
     * @return null|Response
     * @throws TelegramBotException
     */
    function stopMessageLiveLocation(bool $is_inline, string $message_id, string $chat_id = null, string $reply_markup = null): ?Response
    {
        if ($is_inline) {
            $inline_message_id = $message_id;
        } elseif (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\StopMessageLiveLocation(
            $chat_id ?? null,
            $message_id,
            $inline_message_id ?? null,
            $reply_markup ?? null
        ));
    }


    /**
     * @param string $phone_number
     * @param string $first_name
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendContact(string $phone_number, string $first_name, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SendContact(
            $chat_id,
            $phone_number,
            $first_name,
            $params['last_name'] ?? null,
            $params['vcard'] ?? null,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param string $action
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendChatAction(string $action, string $chat_id = null): ?Response
    {
        $available_actions = [
            "typing",
            "upload_photo",
            "record_video",
            "upload_video",
            "record_audio",
            "upload_audio",
            "upload_document",
            "find_location",
            "record_video_note",
            "upload_video_note"
        ];
        if (!in_array($action, $available_actions)) {
            throw new TelegramBotException("Invalid action specified");
        }
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SendChatAction(
            $chat_id,
            $action
        ));
    }


    /**
     * @param int $user_id
     * @param array $params
     * @return null|Response
     */
    function getUserProfilePhotos(int $user_id, array $params = []): ?Response
    {
        return $this->sendRequest(new Methods\GetUserProfilePhotos(
            $user_id,
            $params['offset'] ?? 0,
            $params['limit'] ?? 100
        ));
    }


    /**
     * @param string $file_id
     * @return null|Response
     */
    function getFile(string $file_id): ?Response
    {
        return $this->sendRequest(new Methods\GetFile(
            $file_id
        ));
    }


    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @param int|null $until_date
     * @return null|Response
     * @throws TelegramBotException
     */
    function kickChatMember(int $user_id, string $chat_id = null, int $until_date = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\KickChatMember(
            $chat_id,
            $user_id,
            $until_date ?? null
        ));
    }


    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function unbanChatMember(int $user_id, string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\UnbanChatMember(
            $chat_id,
            $user_id
        ));
    }


    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function restrictChatMember(int $user_id, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\RestrictChatMember(
            $chat_id,
            $user_id,
            $params['until_date'] ?? null,
            $params['can_send_messages'] ?? false,
            $params['can_send_media_messages'] ?? false,
            $params['can_send_other_messages'] ?? false,
            $params['can_add_web_page_previews'] ?? false
        ));
    }


    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function promoteChatMember(int $user_id, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\PromoteChatMember(
            $chat_id,
            $user_id,
            $params['can_change_info'] ?? false,
            $params['can_post_messages'] ?? false,
            $params['can_edit_messages'] ?? false,
            $params['can_delete_messages'] ?? false,
            $params['can_invite_users'] ?? false,
            $params['can_restrict_members'] ?? false,
            $params['can_pin_messages'] ?? false,
            $params['can_promote_members'] ?? false
        ));
    }


    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function exportChatInviteLink(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\ExportChatInviteLink(
            $chat_id
        ));
    }


    /**
     * @param Types\InputFile $chat_photo
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function setChatPhoto(Types\InputFile $chat_photo, string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SetChatPhoto(
            $chat_id,
            $chat_photo
        ));
    }


    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function deleteChatPhoto(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\DeleteChatPhoto(
            $chat_id
        ));
    }


    /**
     * @param string $title
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function setChatTitle(string $title, string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SetChatTitle(
            $chat_id,
            $title
        ));
    }


    /**
     * @param string|null $description
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function setChatDescription(string $description = null, string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SetChatDescription(
            $chat_id,
            $description ?? null
        ));
    }


    /**
     * @param int $message_id
     * @param string|null $chat_id
     * @param bool $disable_notification
     * @return null|Response
     * @throws TelegramBotException
     */
    function pinChatMessage(int $message_id, string $chat_id = null, bool $disable_notification = false): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\PinChatMessage(
            $chat_id,
            $message_id,
            $disable_notification ?? false
        ));
    }


    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function unpinChatMessage(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\UnpinChatMessage(
            $chat_id
        ));
    }


    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function leaveChat(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\LeaveChat(
            $chat_id
        ));
    }


    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function getChat(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\GetChat(
            $chat_id
        ));
    }


    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function getChatAdministrators(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\GetChatAdministrators(
            $chat_id
        ));
    }


    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function getChatMembersCount(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\GetChatMembersCount(
            $chat_id
        ));
    }


    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function getChatMember(int $user_id, string $chat_id = null)
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\GetChatMember(
            $chat_id,
            $user_id
        ));
    }


    /**
     * @param string $sticker_set_name
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function setChatStickerSet(string $sticker_set_name, string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SetChatStickerSet(
            $chat_id,
            $sticker_set_name
        ));
    }


    /**
     * @param int|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function deleteChatStickerSet(int $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }

        return $this->sendRequest(new Methods\DeleteChatStickerSet(
            $chat_id
        ));
    }


    /**
     * @param array $params
     * @param int|null $callback_query_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function answerCallbackQuery(array $params = [], int $callback_query_id = null): ?Response
    {
        if (empty($callback_query_id)) {
            $callback_query_id = $this->getCallbackQueryID();
        }
        return $this->sendRequest(new Methods\AnswerCallbackQuery(
            $callback_query_id,
            $params['text'] ?? null,
            $params['show_alert'] ?? false,
            $params['url'] ?? null,
            $params['cache_time'] ?? 0
        ));
    }


    /**
     * @return string
     * @throws TelegramBotException
     */
    private function getCallbackQueryID(): string
    {
        if (!empty($this->update->callback_query->id)) {
            return $this->update->callback_query->id;
        }
        throw new TelegramBotException('Unable to get Callback Query ID');
    }

    /**
     * @param string $text
     * @param bool $is_inline
     * @param string $message_id
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function editMessageText(string $text, bool $is_inline, string $message_id, string $chat_id = null, array $params = []): ?Response
    {
        if ($is_inline) {
            $inline_message_id = $message_id;
        } elseif (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\EditMessageText(
            $chat_id ?? null,
            $message_id,
            $inline_message_id ?? null,
            $text,
            $params['parse_mode'] ?? null,
            $params['disable_web_page_preview'] ?? false,
            $params['reply_markup'] ?? null
        ));
    }

    /**
     * @param bool $is_inline
     * @param string $message_id
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function editMessageCaption(bool $is_inline, string $message_id, string $chat_id = null, array $params = []): ?Response
    {
        if ($is_inline) {
            $inline_message_id = $message_id;
        } elseif (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\EditMessageCaption(
            $chat_id ?? null,
            $message_id,
            $inline_message_id ?? null,
            $params['caption'] ?? null,
            $params['parse_mode'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }

    /**
     * @param bool $is_inline
     * @param string $message_id
     * @param string|null $chat_id
     * @param Types\InlineKeyboardMarkup $reply_markup
     * @return null|Response
     * @throws TelegramBotException
     */
    function editMessageReplyMarkup(bool $is_inline, string $message_id, string $chat_id = null, Types\InlineKeyboardMarkup $reply_markup = null): ?Response
    {
        if ($is_inline) {
            $inline_message_id = $message_id;
        } elseif (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\EditMessageReplyMarkup(
            $chat_id ?? null,
            $message_id,
            $inline_message_id ?? null,
            $reply_markup ?? null
        ));
    }

    /**
     * @param int $message_id
     * @param int|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function deleteMessage(int $message_id, int $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\DeleteMessage(
            $chat_id,
            $message_id
        ));
    }


    /**
     * @param string $name
     * @return null|Response
     */
    function getStickerSet(string $name): ?Response
    {
        return $this->sendRequest(new Methods\GetStickerSet(
            $name
        ));
    }


    /**
     * @param int $user_id
     * @param Types\InputFile $sticker
     * @return null|Response
     */
    function uploadStickerFile(int $user_id, Types\InputFile $sticker): ?Response
    {
        return $this->sendRequest(new Methods\UploadStickerFile(
            $user_id,
            $sticker
        ));
    }


    /**
     * @param int $user_id
     * @param string $name
     * @param string $title
     * @param Types\InputFile $sticker
     * @param string $emojis
     * @param array $params
     * @return null|Response
     */
    function createNewStickerSet(int $user_id, string $name, string $title, Types\InputFile $sticker, string $emojis, array $params = []): ?Response
    {
        return $this->sendRequest(new Methods\CreateNewStickerSet(
            $user_id,
            $name,
            $title,
            $sticker,
            $emojis,
            $params['contains_masks'] ?? false,
            $params['mask_position'] ?? null
        ));
    }


    /**
     * @param int $user_id
     * @param string $name
     * @param Types\InputFile $sticker
     * @param string $emojis
     * @param Types\MaskPosition|null $mask_position
     * @return null|Response
     */
    function addStickerToSet(int $user_id, string $name, Types\InputFile $sticker, string $emojis, Types\MaskPosition $mask_position = null): ?Response
    {
        return $this->sendRequest(new Methods\AddStickerToSet(
            $user_id,
            $name,
            $sticker,
            $emojis,
            $mask_position ?? null
        ));
    }


    /**
     * @param Types\InputFile $sticker
     * @param int $position
     * @return null|Response
     */
    function setStickerPositionInSet(Types\InputFile $sticker, int $position): ?Response
    {
        return $this->sendRequest(new Methods\SetStickerPositionInSet(
            $sticker,
            $position
        ));
    }


    /**
     * @param Types\InputFile $sticker
     * @return null|Response
     */
    function deleteStickerFromSet(Types\InputFile $sticker): ?Response
    {
        return $this->sendRequest(new Methods\DeleteStickerFromSet(
            $sticker
        ));
    }

    /**
     * @param array $results
     * @param array $params
     * @param string|null $inline_query_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function answerInlineQuery(array $results, array $params = [], string $inline_query_id = null): ?Response
    {
        if (empty($inline_query_id)) {
            $inline_query_id = $this->getInlineQueryID();
        }
        return $this->sendRequest(new Methods\AnswerInlineQuery(
            $inline_query_id,
            $results,
            $params['cache_time'] ?? 300,
            $params['is_personal'] ?? false,
            $params['next_offset'] ?? null,
            $params['switch_pm_text'] ?? null,
            $params['switch_pm_parameter'] ?? null
        ));
    }


    /**
     * @return int
     * @throws TelegramBotException
     */
    private function getInlineQueryID(): int
    {
        if (!empty($this->update->inline_query->id)) {
            return $this->update->inline_query->id;
        }
        throw new TelegramBotException('Unable to get Inline Query ID');
    }


    /**
     * @param string $title
     * @param string $description
     * @param string $payload
     * @param string $provider_token
     * @param string $start_parameter
     * @param string $currency
     * @param array $prices
     * @param array $params
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendInvoice(string $title, string $description, string $payload, string $provider_token, string $start_parameter, string $currency, array $prices, array $params = [], string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SendInvoice(
            $chat_id,
            $title,
            $description,
            $payload,
            $provider_token,
            $start_parameter,
            $currency,
            $prices,
            $params['provider_data'] ?? null,
            $params['photo_url'] ?? null,
            $params['photo_size'] ?? null,
            $params['photo_width'] ?? null,
            $params['photo_height'] ?? null,
            $params['need_name'] ?? false,
            $params['need_phone_number'] ?? false,
            $params['need_email'] ?? false,
            $params['need_shipping_address'] ?? false,
            $params['send_phone_number_to_provider'] ?? false,
            $params['send_email_to_provider'] ?? false,
            $params['is_flexible'] ?? false,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param bool $ok
     * @param array $params
     * @param int|null $shipping_query_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function answerShippingQuery(bool $ok, array $params = [], int $shipping_query_id = null): ?Response
    {
        if (empty($shipping_query_id)) {
            $shipping_query_id = $this->getShippingQueryID();
        }
        if ($ok) {
            if (empty($request_params['shipping_options'])) throw new TelegramBotException('Shipping Options field required');
        } else {
            if (empty($request_params['error_message'])) throw new TelegramBotException('Error message field required');
        }
        return $this->sendRequest(new Methods\AnswerShippingQuery(
            $shipping_query_id,
            $ok,
            $params['shipping_options'] ?? null,
            $params['error_message'] ?? null
        ));
    }


    /**
     * @return int
     * @throws TelegramBotException
     */
    private function getShippingQueryID(): int
    {
        if (!empty($this->update->shipping_query->id)) {
            return $this->update->shipping_query->id;
        }
        throw new TelegramBotException('Unable to get Shipping Query ID');
    }


    /**
     * @param bool $ok
     * @param string|null $error_message
     * @param int|null $pre_checkout_query_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function answerPreCheckoutQuery(bool $ok, string $error_message = null, int $pre_checkout_query_id = null): ?Response
    {
        if (empty($pre_checkout_query_id)) {
            $pre_checkout_query_id = $this->getPreCheckoutQueryID();
        }
        if (!$ok and empty($error_message)) throw new TelegramBotException("Error Message field required");
        return $this->sendRequest(new Methods\AnswerPreCheckoutQuery(
            $pre_checkout_query_id,
            $ok,
            $error_message ?? null
        ));
    }


    /**
     * @return int
     * @throws TelegramBotException
     */
    private function getPreCheckoutQueryID(): int
    {
        if (!empty($this->update->pre_checkout_query->id)) {
            return $this->update->pre_checkout_query->id;
        }
        throw new TelegramBotException('Unable to get Pre-Checkout Query ID');
    }


    /**
     * @param int $user_id
     * @param array $errors
     * @return null|Response
     */
    function setPassportDataErrors(int $user_id, array $errors): ?Response
    {
        return $this->sendRequest(new Methods\SetPassportDataErrors(
            $user_id,
            $errors
        ));
    }


    /**
     * @param string $game_short_name
     * @param array $params
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendGame(string $game_short_name, array $params = [], string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SendGame(
            $chat_id,
            $game_short_name,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param int $user_id
     * @param int $score
     * @param bool $is_inline
     * @param string $message_id
     * @param array $params
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function setGameScore(int $user_id, int $score, bool $is_inline, string $message_id, array $params = [], string $chat_id = null): ?Response
    {
        if ($is_inline) {
            $inline_message_id = $message_id;
        } elseif (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\SetGameScore(
            $user_id,
            $score,
            $chat_id ?? null,
            $message_id,
            $inline_message_id ?? null,
            $params['force'] ?? false,
            $params['disable_edit_message'] ?? false
        ));
    }


    /**
     * @param int $user_id
     * @param bool $is_inline
     * @param int $message_id
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    function getGameHighScores(int $user_id, bool $is_inline, int $message_id, string $chat_id = null): ?Response
    {
        if ($is_inline) {
            $inline_message_id = $message_id;
        } elseif (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\GetGameHighScores(
            $user_id,
            $chat_id ?? null,
            $message_id,
            $inline_message_id ?? null
        ));
    }


    /**
     *
     * @throws TelegramBotException
     */
    function start(): void
    {
        $is_logging = $this->logger->getVerbosity() === 2 ? true : false;
        if ($this->use_polling) {
            if ($is_logging) {
                $this->logger->log("TelegramBot: Long Polling mode started.");
            }
            $offset = 0;
            foreach ($this->settings->getGeneralSection()->getAdminHandler()->getAdmins() as $admin) {
                $this->sendMessage("Bot started correctly.", $admin);
            }
            while (true) {
                $response = $this->getUpdates(['offset' => $offset]);
                if (null === $response->result) {
                    continue;
                }
                foreach ($response->result as $update) {
                    $offset = $update->update_id;
                    if ($this->settings->getMaintenanceSection()->isEnabled() and $update->message->chat->type === "private") {
                        $this->sendMessage($this->settings->getMaintenanceSection()->getMessage());
                        continue;
                    }
                    if (isset($update->message->text) and $update->message->text === "/halt") {
                        $this->sendMessage("Shutting down...");
                        $offset++;
                        $this->getUpdates(['offset' => $offset, 'limit' => 1]);
                        exit;
                    }
                    $results = $this->processUpdate($update);
                    if (!empty($results)) $this->processResults($results);
                    $offset++;
                }
            }
        } else {
            if ($is_logging) {
                $this->logger->log("TelegramBot: Webhook mode started.");
            }
            $results = $this->processUpdate($this->update);
            if (!empty($results)) $this->processResults($results);
        }
        return;
    }


    /**
     * @param string $text
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    function sendMessage(string $text, string $chat_id = null, array $params = []): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        if (!isset($params['parse_mode'])) {
            $params['parse_mode'] = $this->settings->getTelegramSection()->getParseMode();
        }
        return $this->sendRequest(new Methods\SendMessage(
            $chat_id,
            $text,
            $params['parse_mode'] ?? null,
            $params['disable_web_page_preview'] ?? false,
            $params['disable_notification'] ?? false,
            $params['reply_to_message_id'] ?? null,
            $params['reply_markup'] ?? null
        ));
    }


    /**
     * @param array $params
     * @return null|Response
     */
    function getUpdates(array $params = []): ?Response
    {
        return $this->sendRequest(new Methods\GetUpdates(
            $params['offset'] ?? 0,
            $params['limit'] ?? 100,
            $params['timeout'] ?? 0,
            $params['allowed_updates'] ?? []
        ));
    }


    /**
     * @param Update $update
     * @return array
     */
    private function processUpdate(Update $update): array
    {
        if ($this->logger->getVerbosity() === 2) {
            $log_message = sprintf("TelegramBot: New Update received. (%s)", json_encode($update, JSON_UNESCAPED_SLASHES));
            $this->logger->log($log_message);
        }
        $this->update = $update;
        $results = $this->event_handler->processEvents($this, $this->update);
        return $results;
    }

    /**
     * @param array $results
     */
    private function processResults(array $results): void
    {
        if (!$this->results_callback instanceof \Closure) return;
        $results_callback = $this->results_callback;
        foreach ($results as $result) {
            $results_callback($result);
        }
        return;
    }

    /**
     * @param \Closure $callback
     * @return TelegramBot
     */
    public function setResultsCallback(\Closure $callback): self
    {
        $this->results_callback = $callback;
        return $this;
    }

    /**
     * @param array $update_path
     * @param string $command
     * @param \Closure $callback
     * @param bool $auto_add
     * @return DefaultEvent
     * @throws Exception\EventException
     */
    public function createSimpleEvent(array $update_path, string $command, \Closure $callback, bool $auto_add = true): DefaultEvent
    {
        $trigger = new Trigger($command);
        $event = (new class($trigger, $callback, $update_path) extends DefaultEvent
        {
            /**
             * @var array
             */
            public static $update_path;
            /**
             * @var string
             */
            public static $type;

            /**
             * Class constructor.
             * @param Trigger $trigger
             * @param \Closure $callback
             * @param array $final_update_path
             */
            function __construct(Trigger $trigger, \Closure $callback, array $final_update_path)
            {
                parent::__construct($trigger, $callback);
                $this::$update_path = $final_update_path;
                $final_type = str_replace('edited_', '', $final_update_path[0]);
                $final_type = str_replace('_', '', ucwords($final_type, '_'));
                if ($final_type === 'ChannelPost') $final_type = 'Message';
                $final_type = sprintf('TelegramBot\Telegram\Types\%s', $final_type);
                $this::$type = $final_type;
            }
        });
        if ($auto_add) $this->addEvent($event);
        return $event;
    }

    /**
     * @param DefaultEvent $event
     */
    public function addEvent(DefaultEvent $event): void
    {
        $this->event_handler->addEvent($event);
        return;
    }

    /**
     * @param DefaultEvent $event
     * @return bool
     */
    public function removeEvent(DefaultEvent $event): bool
    {
        return $this->event_handler->removeEvent($event);
    }
}