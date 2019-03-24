<?php

namespace TelegramBot;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use TelegramBot\{DBEntity\Chat,
    DBEntity\User,
    Event\CallbackQueryEvent,
    Event\DefaultEvent,
    Event\EventHandler,
    Event\MessageTextEvent,
    Event\Trigger,
    Exception\EventException,
    Exception\LocalizationProviderException,
    Exception\SettingsProviderException,
    Exception\TelegramBotException,
    Localization\LocalizationProvider,
    Telegram\Methods,
    Telegram\Types,
    Telegram\Types\Response,
    Telegram\Types\Update,
    Util\KeyboardUtil};

/**
 * Class TelegramBot
 * @package TelegramBot
 */
class TelegramBot
{

    /**
     *
     */
    public const BOT_VERSION = "1.0.3";
    /**
     *
     */
    private const ENDPOINT = "https://api.telegram.org/";
    /**
     * @var array
     */
    public $shared_data = [];
    /**
     * @var bool
     */
    public $auto_flush = true;
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
    private $settings_provider = null;
    /**
     * @var EntityManager|null
     */
    private $entity_manager = null;
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
     * @var null|\Closure
     */
    private $results_callback = null;
    /**
     * @var LocalizationProvider|null
     */
    private $localization_provider = null;

    /**
     * TelegramBot constructor.
     * @param string $token
     * @param EntityManager $entity_manager
     * @param string $settings_path
     * @param int $log_verbosity
     * @param string|null $log_path
     * @param bool $force_log_output
     * @throws EventException
     * @throws LocalizationProviderException
     * @throws SettingsProviderException
     * @throws TelegramBotException
     * @throws \Exception
     */
    function __construct(
        string $token,
        EntityManager $entity_manager,
        string $settings_path = "config/settings.json",
        int $log_verbosity = 0,
        string $log_path = null,
        bool $force_log_output = false
    ) {
        $this->logger = new Logger($log_verbosity, $log_path, $force_log_output);
        $this->exception_handler = new ExceptionHandler($this->logger);
        $this->token = str_replace("bot", "", $token);
        $this->settings_provider = new SettingsProvider($this->logger, $settings_path);
        $this->entity_manager = $entity_manager;
        $this->event_handler = new EventHandler();
        if (file_exists($settings_path)) {
            $this->settings = $this->settings_provider->loadSettings()->getSettings();
        } else {
            $this->settings = $this->settings_provider->createSettings()->getSettings();
            $this->settings_provider->saveSettings();
        }
        $general_settings = $this->settings->getGeneralSection();
        $this->localization_provider = new LocalizationProvider($general_settings->getDefaultLocale(),
            $general_settings->getDefaultLocalePath());
        $this->addLanguages();
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
            throw new TelegramBotException("Invalid bot token provided");
        }
        if (isset($_GET['show_dashboard'])) {
            $this->checkAuthorization($_GET['show_dashboard']);
            $this->showDashboard();
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
        $this->addInternalEvents();
        $shutdown_callback = function () {
            if (!empty($this->shared_data)) {
                $basic_data = new DBEntity\BasicData();
                $basic_data->setName("shared_data");
                $basic_data->setValue($this->shared_data);
                $this->entity_manager->persist($basic_data);
                $this->entity_manager->commit();
            }
        };
        register_shutdown_function($shutdown_callback);
    }

    /**
     * @throws LocalizationProviderException
     */
    private function addLanguages(): void
    {
        $languages = new \FilesystemIterator("languages", \FilesystemIterator::SKIP_DOTS);
        foreach ($languages as $language) {
            $locale = trim($language->getFilename());
            $locale = strtolower($locale);
            $locale = substr($locale, 0, 2);
            $this->localization_provider->addLanguage($locale, $language);
        }
    }

    /**
     * @return null|Response
     */
    public function getMe(): ?Response
    {
        return $this->sendRequest(new Methods\GetMe());
    }

    /**
     * @param Methods\MethodInterface $method
     * @return null|Response
     */
    public function sendRequest(Methods\MethodInterface $method): ?Response
    {
        $method_name = $method->getMethodName();
        $params = $method->getParams();
        $params = array_filter($params, function ($value) {
            return null != $value;
        });
        if (isset($params['reply_markup'])) {
            $params['reply_markup'] = json_encode($params['reply_markup']);
        }
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
            if ($result_params['multiple']) {
                $method_name = sprintf('%ss', $method_name);
            }
            $result_type = (object)[
                'class' => $result_name,
                'method' => $method_name
            ];
            $result->result_type = $result_type;
        }
        return Response::parseResponse($result);
    }

    /**
     * @param string $password
     * @throws TelegramBotException
     */
    private function checkAuthorization(string $password): void
    {
        $hashed_password = hash('sha512', $password);
        $session_started = session_start();
        $auth_hash = $_SESSION['auth_hash'] ?? null;
        if (null == $auth_hash) {
            $key = ftok(__FILE__, 't');
            $mem = shmop_open($key, 'c', 0777, 138);
            $timestamp = shmop_read($mem, 0, 10);
            $auth_hash = shmop_read($mem, 10, 128);
            if (empty($auth_hash)) {
                throw new TelegramBotException('Authorization token unavailable, generate one first');
            }
            $time_diff = time() - $timestamp;
            if ($time_diff >= 300) {
                shmop_delete($mem);
                throw new TelegramBotException('Authorization token expired, generate another one');
            }
            if ($hashed_password != $auth_hash) {
                throw new TelegramBotException('Invalid authorization token provided');
            }
            if ($session_started) {
                $_SESSION['auth_hash'] = $auth_hash;
                $this->logger->log('TelegramBot: Authorization token verified, request granted; session registered successfully.');
                shmop_delete($mem);
                return;
            }
            $message = sprintf("TelegramBot: Authorization token verified, request granted; session could not be registered (token will be valid only for %s seconds).",
                $time_diff);
            $this->logger->log($message);
            return;
        }
        if ($hashed_password != $auth_hash) {
            throw new TelegramBotException('Invalid authorization token provided');
        }
        $this->logger->log('TelegramBot: Authorization token verified, request granted; session resumed successfully.');
        return;
    }

    /**
     *
     */
    private function showDashboard(): void
    {
        /** @noinspection PhpIncludeInspection */
        include 'assets/dashboard.php';
    }

    /**
     * @return null|Response
     */
    public function getWebhookInfo(): ?Response
    {
        return $this->sendRequest(new Methods\GetWebhookInfo());
    }

    /**
     * @throws TelegramBotException
     */
    private function checkRequest(): void
    {
        $is_logging = $this->logger->getVerbosity() >= 1 ? true : false;
        if (isset($_GET['bypass_check'])) {
            $this->checkAuthorization($_GET['bypass_check']);
            return;
        }
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        if (null == $_SERVER['REMOTE_ADDR']) {
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
     * @throws EventException
     */
    private function addInternalEvents(): void
    {
        $auth_trigger = new Trigger('/gen_auth');
        $auth_event = new MessageTextEvent($auth_trigger, function () {
            $auth_token = bin2hex(random_bytes(8));
            $key = ftok(__FILE__, 't');
            $mem = shmop_open($key, 'c', 0777, 138);
            shmop_write($mem, time(), 0);
            $auth_hash = hash('sha512', $auth_token);
            shmop_write($mem, $auth_hash, 10);
            $text = sprintf('Your authorization token is: <code>%s</code>.%sIt will connect your web browser to this user profile, and it will expire after using it (or after 300 seconds).',
                $auth_token, PHP_EOL);
            $this->sendMessage($text);
        }, true);
        $change_locale_trigger = new Trigger('/change_locale');
        $change_locale_event = new MessageTextEvent($change_locale_trigger, function () {
            $locale = $this->updateUserLocale();
            if (null != $locale) {
                $text = sprintf('Cannot change locale, there is only one language available: <code>%s</code>.',
                    $locale);
                $this->sendMessage($text);
            }
        });
        $set_locale_trigger = new Trigger('set_locale:', true, false);
        $set_locale_event = new CallbackQueryEvent($set_locale_trigger, function () {
            $callback_query = $this->update->callback_query;
            $locale = explode(':', $callback_query->data)[1];
            $this->addUser($callback_query->from->id, $locale);
            $text = sprintf('Language set. From now on, you are using: <code>%s</code>.', $locale);
            $this->editMessageText($text, false, $callback_query->message->message_id);
        });
        $this->event_handler->addInternalEvent($auth_event);
        $this->event_handler->addInternalEvent($set_locale_event);
        $this->event_handler->addInternalEvent($change_locale_event);
    }

    /**
     * @param string $text
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    public function sendMessage(string $text, string $chat_id = null, array $params = []): ?Response
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
     * @return mixed
     * @throws TelegramBotException
     */
    private function updateUserLocale(): ?string
    {
        $available_languages = $this->localization_provider->getLanguages();
        if (1 === count($available_languages)) {
            return $available_languages[0];
        }
        $callback = 'set_locale:%s';
        $buttons = [];
        foreach ($available_languages as $available_language) {
            $buttons[] = KeyboardUtil::generateInlineKeyboardButton($available_language, 'callback_data',
                sprintf($callback, $available_language));
        }
        $total_buttons = count($buttons);
        $columns = floor(sqrt($total_buttons));
        $rows_count = ceil($total_buttons / $columns);
        $rows = array_chunk($buttons, $rows_count);
        $keyboard = [];
        foreach ($rows as $row) {
            $keyboard[] = KeyboardUtil::generateInlineKeyboardRow($row);
        }
        $this->sendMessage("Select your language:", null,
            ["reply_markup" => KeyboardUtil::generateInlineKeyboard($keyboard)]);
        return null;
    }

    /**
     * @param int $user_id
     * @param string $language_code
     * @param bool $blocked
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function addUser(int $user_id, string $language_code, bool $blocked = false): void
    {
        $user = $this->entity_manager->getRepository('TelegramBot\DBEntity\User')->find($user_id);
        if (null == $user) {
            $user = new DBEntity\User();
        }
        $user->setId($user_id);
        $user->setLanguageCode($language_code);
        $user->setBlocked($blocked);
        $this->entity_manager->persist($user);
        if (true == $this->auto_flush) {
            $this->entity_manager->flush();
        }
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
    public function editMessageText(
        string $text,
        bool $is_inline,
        string $message_id,
        string $chat_id = null,
        array $params = []
    ): ?Response {
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
     * @param array $update_path
     * @param string $command
     * @param \Closure $callback
     * @param bool $admins_only
     * @return DefaultEvent
     * @throws Exception\EventException
     */
    public function createSimpleEvent(
        array $update_path,
        string $command,
        \Closure $callback,
        bool $admins_only = false
    ): DefaultEvent {
        $trigger = new Trigger($command);
        $event = (new class($trigger, $callback, $admins_only, $update_path) extends DefaultEvent
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
             * @param bool $admins_only
             * @param array $final_update_path
             */
            function __construct(Trigger $trigger, \Closure $callback, bool $admins_only, array $final_update_path)
            {
                parent::__construct($trigger, $callback, $admins_only);
                $this::$update_path = $final_update_path;
                $final_type = str_replace('edited_', '', $final_update_path[0]);
                $final_type = str_replace('_', '', ucwords($final_type, '_'));
                if ($final_type === 'ChannelPost') {
                    $final_type = 'Message';
                }
                $final_type = sprintf('TelegramBot\Telegram\Types\%s', $final_type);
                $this::$type = $final_type;
            }
        });
        $this->addEvent($event);
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
     * @return EntityManager|null
     */
    public function getEntityManager(): ?EntityManager
    {
        return $this->entity_manager;
    }

    /**
     *
     */
    public function resetPeers(): void
    {
        $this->entity_manager->createQuery('delete from TelegramBot\DBEntity\Chat')->execute();
        $this->entity_manager->createQuery('delete from TelegramBot\DBEntity\User')->execute();
        return;
    }

    /**
     * @return null|SettingsProvider
     */
    public function getProvider(): ?SettingsProvider
    {
        return $this->settings_provider;
    }

    /**
     *
     */
    public function refreshSettings(): void
    {
        $this->settings = $this->settings_provider->getSettings();
        return;
    }

    /**
     * @param string $url
     * @param array $params
     * @return null|Response
     */
    public function setWebhook(string $url, array $params = []): ?Response
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
    public function deleteWebhook(): ?Response
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
    public function forwardMessage(
        string $chat_id,
        string $from_chat_id,
        int $message_id,
        bool $disable_notification = false
    ): ?Response {
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
    public function sendPhoto(Types\InputFile $photo, string $chat_id = null, array $params = []): ?Response
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
     * @param Types\InputFile $audio
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    public function sendAudio(Types\InputFile $audio, string $chat_id = null, array $params = []): ?Response
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
    public function sendDocument(Types\InputFile $document, string $chat_id = null, array $params = []): ?Response
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
    public function sendVideo(Types\InputFile $video, string $chat_id = null, array $params = []): ?Response
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
    public function sendVideoNote(Types\InputFile $video_note, string $chat_id = null, array $params = []): ?Response
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
    public function sendVoice(Types\InputFile $voice, string $chat_id = null, array $params = []): ?Response
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
    public function sendSticker(Types\InputFile $sticker, string $chat_id = null, array $params = []): ?Response
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
    public function sendMediaGroup(array $media, string $chat_id = null, array $params = []): ?Response
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
    public function sendLocation(
        float $latitude,
        float $longitude,
        string $chat_id = null,
        array $params = []
    ): ?Response {
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
    public function sendVenue(
        float $latitude,
        float $longitude,
        string $title,
        string $address,
        string $chat_id = null,
        array $params = []
    ): ?Response {
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
     * @param Types\InlineKeyboardMarkup $reply_markup
     * @return null|Response
     * @throws TelegramBotException
     */
    public function editMessageLiveLocation(
        float $latitude,
        float $longitude,
        bool $is_inline,
        string $message_id,
        string $chat_id = null,
        Types\InlineKeyboardMarkup $reply_markup = null
    ): ?Response {
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
     * @param Types\InlineKeyboardMarkup $reply_markup
     * @return null|Response
     * @throws TelegramBotException
     */
    public function stopMessageLiveLocation(
        bool $is_inline,
        string $message_id,
        string $chat_id = null,
        Types\InlineKeyboardMarkup $reply_markup = null
    ): ?Response {
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
    public function sendContact(
        string $phone_number,
        string $first_name,
        string $chat_id = null,
        array $params = []
    ): ?Response {
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
    public function sendChatAction(string $action, string $chat_id = null): ?Response
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
    public function getUserProfilePhotos(int $user_id, array $params = []): ?Response
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
    public function getFile(string $file_id): ?Response
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
    public function kickChatMember(int $user_id, string $chat_id = null, int $until_date = null): ?Response
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
    public function unbanChatMember(int $user_id, string $chat_id = null): ?Response
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
    public function restrictChatMember(int $user_id, string $chat_id = null, array $params = []): ?Response
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
    public function promoteChatMember(int $user_id, string $chat_id = null, array $params = []): ?Response
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
    public function exportChatInviteLink(string $chat_id = null): ?Response
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
    public function setChatPhoto(Types\InputFile $chat_photo, string $chat_id = null): ?Response
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
    public function deleteChatPhoto(string $chat_id = null): ?Response
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
    public function setChatTitle(string $title, string $chat_id = null): ?Response
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
    public function setChatDescription(string $description = null, string $chat_id = null): ?Response
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
    public function pinChatMessage(
        int $message_id,
        string $chat_id = null,
        bool $disable_notification = false
    ): ?Response {
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
    public function unpinChatMessage(string $chat_id = null): ?Response
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
    public function leaveChat(string $chat_id = null): ?Response
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
    public function getChat(string $chat_id = null): ?Response
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
    public function getChatMembersCount(string $chat_id = null): ?Response
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
    public function getChatMember(int $user_id, string $chat_id = null)
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
    public function setChatStickerSet(string $sticker_set_name, string $chat_id = null): ?Response
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
    public function deleteChatStickerSet(int $chat_id = null): ?Response
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
    public function answerCallbackQuery(array $params = [], int $callback_query_id = null): ?Response
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
     * @param bool $is_inline
     * @param string $message_id
     * @param string|null $chat_id
     * @param array $params
     * @return null|Response
     * @throws TelegramBotException
     */
    public function editMessageCaption(
        bool $is_inline,
        string $message_id,
        string $chat_id = null,
        array $params = []
    ): ?Response {
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
    public function editMessageReplyMarkup(
        bool $is_inline,
        string $message_id,
        string $chat_id = null,
        Types\InlineKeyboardMarkup $reply_markup = null
    ): ?Response {
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
    public function deleteMessage(int $message_id, int $chat_id = null): ?Response
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
    public function getStickerSet(string $name): ?Response
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
    public function uploadStickerFile(int $user_id, Types\InputFile $sticker): ?Response
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
    public function createNewStickerSet(
        int $user_id,
        string $name,
        string $title,
        Types\InputFile $sticker,
        string $emojis,
        array $params = []
    ): ?Response {
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
    public function addStickerToSet(
        int $user_id,
        string $name,
        Types\InputFile $sticker,
        string $emojis,
        Types\MaskPosition $mask_position = null
    ): ?Response {
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
    public function setStickerPositionInSet(Types\InputFile $sticker, int $position): ?Response
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
    public function deleteStickerFromSet(Types\InputFile $sticker): ?Response
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
    public function answerInlineQuery(array $results, array $params = [], string $inline_query_id = null): ?Response
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
    public function sendInvoice(
        string $title,
        string $description,
        string $payload,
        string $provider_token,
        string $start_parameter,
        string $currency,
        array $prices,
        array $params = [],
        string $chat_id = null
    ): ?Response {
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
    public function answerShippingQuery(bool $ok, array $params = [], int $shipping_query_id = null): ?Response
    {
        if (empty($shipping_query_id)) {
            $shipping_query_id = $this->getShippingQueryID();
        }
        if ($ok) {
            if (empty($request_params['shipping_options'])) {
                throw new TelegramBotException('Shipping Options field required');
            }
        } else {
            if (empty($request_params['error_message'])) {
                throw new TelegramBotException('Error message field required');
            }
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
    public function answerPreCheckoutQuery(
        bool $ok,
        string $error_message = null,
        int $pre_checkout_query_id = null
    ): ?Response {
        if (empty($pre_checkout_query_id)) {
            $pre_checkout_query_id = $this->getPreCheckoutQueryID();
        }
        if (!$ok and empty($error_message)) {
            throw new TelegramBotException("Error Message field required");
        }
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
    public function setPassportDataErrors(int $user_id, array $errors): ?Response
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
    public function sendGame(string $game_short_name, array $params = [], string $chat_id = null): ?Response
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
    public function setGameScore(
        int $user_id,
        int $score,
        bool $is_inline,
        string $message_id,
        array $params = [],
        string $chat_id = null
    ): ?Response {
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
    public function getGameHighScores(int $user_id, bool $is_inline, int $message_id, string $chat_id = null): ?Response
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function start(): void
    {
        $is_logging = $this->logger->getVerbosity() === 2 ? true : false;
        if ($this->use_polling) {
            if ($is_logging) {
                $this->logger->log("TelegramBot: Long Polling mode started.");
            }
            $offset = 0;
            $admin_handler = $this->settings->getGeneralSection()->getAdminHandler();
            foreach ($admin_handler->getAdmins() as $admin) {
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
                    if (isset($update->message->text) and $update->message->text === "/halt" and $admin_handler->isAdmin($update->message->from->id)) {
                        $this->sendMessage("Shutting down...");
                        $offset++;
                        $this->getUpdates(['offset' => $offset, 'limit' => 1]);
                        exit;
                    }
                    $results = $this->processUpdate($update);
                    if (!empty($results)) {
                        $this->processResults($results);
                    }
                    $offset++;
                }
            }
        } else {
            if ($is_logging) {
                $this->logger->log("TelegramBot: Webhook mode started.");
            }
            $results = $this->processUpdate($this->update);
            if (!empty($results)) {
                $this->processResults($results);
            }
        }
        return;
    }

    /**
     * @param array $params
     * @return null|Response
     */
    public function getUpdates(array $params = []): ?Response
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
     * @throws TelegramBotException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function processUpdate(Update $update): array
    {
        if ($this->logger->getVerbosity() === 2) {
            $log_message = sprintf("TelegramBot: New Update received. (%s)",
                json_encode($update, JSON_UNESCAPED_SLASHES));
            $this->logger->log($log_message);
        }
        $this->update = $update;
        $update_data = $update->{$update->type};
        $user = $update_data->from;
        $addons_result = $this->event_handler->processAddons($this, $this->update);
        if (!$addons_result) {
            return [];
        }
        $this->event_handler->processInternalEvents($this, $this->update);
        $user_data = $this->getUserData($user->id);
        if (null == $user_data or null == $user_data->getLanguageCode()) {
            $language_code = $this->getUserLocale($user);
            if (null == $language_code) {
                return [];
            }
            $this->addUser($user->id, $language_code);
        }
        if (!empty($update_data->chat) and $update_data->chat->id < 0) {
            $admins_data = $this->getChatAdministrators($update_data->chat->id);
            $admins = array_map(function ($obj) {
                return $obj->user->id;
            }, (array)$admins_data->result);
            $this->addChat($update_data->chat->id, $update_data->chat->type, $admins);
        }
        $results = $this->event_handler->processEvents($this, $this->update);
        return $results;
    }

    /**
     * @param int $user_id
     * @return object|null
     */
    public function getUserData(int $user_id): ?User
    {
        return $this->entity_manager->getRepository('TelegramBot\DBEntity\User')->find($user_id);
    }

    /**
     * @param Types\User $user
     * @return string
     * @throws TelegramBotException
     */
    private function getUserLocale(Types\User $user): ?string
    {
        $locale = $this->getUserData($user->id) ?? $user->language_code ?? null;
        if (is_object($locale)) {
            return $locale->getLanguageCode();
        }
        if (null != $locale) {
            return $locale;
        }
        return $this->updateUserLocale();
    }

    /**
     * @param string|null $chat_id
     * @return null|Response
     * @throws TelegramBotException
     */
    public function getChatAdministrators(string $chat_id = null): ?Response
    {
        if (empty($chat_id)) {
            $chat_id = $this->getChatID();
        }
        return $this->sendRequest(new Methods\GetChatAdministrators(
            $chat_id
        ));
    }

    /**
     * @param int $chat_id
     * @param string $type
     * @param array $admins
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function addChat(int $chat_id, string $type, array $admins): void
    {
        $chat = $this->entity_manager->getRepository('TelegramBot\DBEntity\Chat')->find($chat_id);
        if (null == $chat) {
            $chat = new DBEntity\Chat();
        }
        $chat->setId($chat_id);
        $chat->setType($type);
        $chat->setAdmins($admins);
        $this->entity_manager->persist($chat);
        if (true == $this->auto_flush) {
            $this->entity_manager->flush();
        }
    }

    /**
     * @param array $results
     */
    private function processResults(array $results): void
    {
        if (!$this->results_callback instanceof \Closure) {
            return;
        }
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
     * @param DefaultEvent $event
     * @return bool
     */
    public function removeEvent(DefaultEvent $event): bool
    {
        return $this->event_handler->removeEvent($event);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flushData(): void
    {
        $this->entity_manager->flush();
        return;
    }

    /**
     * @return Update|null
     */
    public function getCurrentUpdate(): ?Update
    {
        return $this->update;
    }

    /**
     * @param string $key
     * @param string|null $locale
     * @return string
     * @throws LocalizationProviderException
     * @throws TelegramBotException
     */
    public function getLanguageValue(string $key, string $locale = null): string
    {
        $update_data = $this->update->{$this->update->type};
        $user = $update_data->from;
        if (null == $locale) {
            $locale = $this->getUserLocale($user);
        }
        if (null == $locale) {
            throw new TelegramBotException("Could not get user locale");
        }
        return $this->localization_provider->getLanguageField($locale, $key);
    }

    /**
     * @param int $chat_id
     * @return object|null
     */
    public function getChatData(int $chat_id): ?Chat
    {
        return $this->entity_manager->getRepository('TelegramBot\DBEntity\Chat')->find($chat_id);
    }
}
