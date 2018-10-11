<?php

/** @noinspection PhpIncludeInspection */
require 'vendor/autoload.php';
require_once "settingsProvider.php";

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TelegramBotException Extends Exception
{
}

class TelegramBot
{
    private const ENDPOINT = "https://api.telegram.org/";
    private $token = null;
    private $client = null;
    private $provider = null;
    private $hooks = null;
    private $settings = null;
    private $update = null;
    private $use_polling = false;

    /**
     * TelegramBot constructor.
     * @param string $token
     * @param string $settings_path
     * @throws TelegramBotException
     * @throws SettingsProviderException
     */
    function __construct(string $token, string $settings_path = "data/management/bot.settings")
    {
        $this->token = sprintf("bot%s", str_replace("bot", "", $token));
        $this->provider = new settingsProvider($settings_path);
        if (file_exists($settings_path)) {
            $this->settings = $this->provider->loadSettings();
        } else {
            $this->settings = $this->provider->createSettings();
            $this->provider->saveSettings($this->settings);
        }
        if ($this->settings->telegram->use_test_api) $this->token = $this->token . "/test";
        $this->client = new Client([
            'base_uri' => self::ENDPOINT . $this->token . '/',
            'timeout' => 2.0
        ]);
        if (!$this->getMe()->ok) throw new TelegramBotException("Invalid token provided");
        $raw_update = file_get_contents("php://input");
        if (empty($this->getWebhookInfo()->result->url) and empty($raw_update)) {
            $this->use_polling = true;
        } else {
            $this->update = json_decode($raw_update);
        }
    }

    /**
     * @return stdClass
     */
    function getMe(): stdClass
    {
        return $this->sendRequest("getMe", []);
    }

    /**
     * @param string $method
     * @param array $params
     * @return stdClass
     */
    function sendRequest(string $method, array $params): stdClass
    {
        try {
            $response = $this->client->post($method, [
                'form_params' => $params,
                'headers' => [
                    'Connection' => 'Keep-Alive',
                    'Keep-Alive' => '120',
                ]
            ]);
        } catch (ClientException $e) {
            return (object)["ok" => false];
        }

        return json_decode($response->getBody());
    }

    /**
     * @return stdClass
     */
    function getWebhookInfo(): stdClass
    {
        return $this->sendRequest("getWebhookInfo", []);
    }

    /**
     * @param string $update_type
     * @param string $command
     * @param callable $action
     * @throws TelegramBotException
     */
    function setHook(string $update_type, string $command, callable $action): void
    {
        $available_types = ["message::text", "message::caption", "edited_message::text", "edited_message::caption", "channel_post::text", "channel_post::caption", "edited_channel_post::text", "edited_channel_post::caption", "inline_query::query", "chosen_inline_result::query", "callback_query::data"];
        if (!in_array($update_type, $available_types)) throw new TelegramBotException("Invalid update type");
        $this->hooks[$update_type][$command][] = $action;
        return;
    }

    /**
     * @param stdClass $settings
     * @throws SettingsProviderException
     */
    function refreshSettings(stdClass $settings): void
    {
        $this->provider->validateSettings($settings);
        $this->settings = $settings;
        $this->provider->saveSettings($settings);
        return;
    }

    /**
     * @param string $url
     * @param array $params
     * @return stdClass
     */
    function setWebhook(string $url, array $params = []): stdClass
    {
        $request_params["url"] = $url;
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if (!empty($request_params["certificate"])) {
            $request_params["certificate"] = fopen($request_params["certificate"], 'r');
        }
        $response = $this->sendRequest("setWebhook", $request_params);
        return $response;
    }

    /**
     * @return stdClass
     */
    function deleteWebhook(): stdClass
    {
        return $this->sendRequest("deleteWebhook", []);
    }

    /**
     * @param string $text
     * @param bool $request_contact
     * @param bool $request_location
     * @return array
     */
    function generateReplyKeyboardButton(string $text, bool $request_contact = false, bool $request_location = false): array
    {
        return ["text" => $text,
            "request_contact" => $request_contact,
            "request_location" => $request_location
        ];
    }

    /**
     * @param array $buttons
     * @return array
     */
    function generateReplyKeyboardRow(array $buttons): array
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        return $row;
    }

    /**
     * @param array $rows
     * @param array $params
     * @return string
     */
    function generateReplyKeyboard(array $rows, array $params = []): string
    {
        $keyboard = [];
        foreach ($params as $param => $value) {
            $keyboard[$param] = $value;
        }
        $keyboard_buttons = [];
        foreach ($rows as $row) {
            $keyboard_buttons[] = $row;
        }
        $keyboard["keyboard"] = $keyboard_buttons;
        return json_encode($keyboard);
    }

    /**
     * @param bool $remove_keyboard
     * @param bool $selective
     * @return string
     */
    function generateReplyKeyboardRemove(bool $remove_keyboard = true, bool $selective = false): string
    {
        return json_encode(["remove_keyboard" => $remove_keyboard,
            "selective" => $selective
        ]);
    }

    /**
     * @param bool $force_reply
     * @param bool $selective
     * @return string
     */
    function generateForceReply(bool $force_reply = true, bool $selective = false): string
    {
        return json_encode(["force_reply" => $force_reply,
            "selective" => $selective
        ]);
    }

    /**
     * @param string $text
     * @param string $callback_action
     * @param string $callback_content
     * @return array
     * @throws TelegramBotException
     */
    function generateInlineKeyboardButton(string $text, string $callback_action, string $callback_content): array
    {
        $allowed_actions = ["url", "callback_data", "switch_inline_query", "switch_inline_query_current_chat", "callback_game", "pay"];
        if (!in_array($callback_action, $allowed_actions)) throw new TelegramBotException("Invalid callback action provided");
        return ["text" => $text,
            $callback_action => $callback_content
        ];
    }

    /**
     * @param array $buttons
     * @return array
     */
    function generateInlineKeyboardRow(array $buttons): array
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        return $row;
    }

    /**
     * @param array $rows
     * @return string
     */
    function generateInlineKeyboard(array $rows): string
    {
        $keyboard = [];
        foreach ($rows as $row) {
            $keyboard[] = $row;
        }
        return json_encode(['inline_keyboard' => $keyboard]);
    }

    /**
     * @param string $chat_id
     * @param string $from_chat_id
     * @param int $message_id
     * @param bool $disable_notification
     * @return stdClass
     */
    function forwardMessage(string $chat_id, string $from_chat_id, int $message_id, bool $disable_notification = false): stdClass
    {
        return $this->sendRequest("forwardMessage", ["chat_id" => $chat_id,
            "from_chat_id" => $from_chat_id,
            "message_id" => $message_id,
            "disable_notification" => $disable_notification
        ]);
    }

    /**
     * @param string $type
     * @param string $file
     * @param bool $is_local
     * @return array
     * @throws TelegramBotException
     */
    function generateFile(string $type, string $file, bool $is_local): array
    {
        $available_types = ["photo", "audio", "voice", "video", "video_note", "document", "sticker", "animation"];
        if (!in_array($type, $available_types)) throw new TelegramBotException("Invalid file type provided");
        return ["type" => $type,
            "file" => $file,
            "is_local" => $is_local];
    }

    /**
     * @param array $input_file
     * @param string|null $chat_id
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendFile(array $input_file, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $available_types = ["photo", "audio", "voice", "video", "video_note", "document", "sticker", "animation"];
        if (!in_array($input_file["type"], $available_types) or empty($input_file["file"] or empty($input_file["is_local"]))) throw new TelegramBotException("Invalid input file provided");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        if (empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        if ($input_file["is_local"]) {
            $request_params[$input_file["type"]] = fopen($input_file["file"], 'r');
            if ($input_file["type"] == "video_note") $input_file["type"] = "videoNote";
            return $this->sendRequest("send" . $input_file["type"], $request_params);
        }
        $request_params[$input_file["type"]] = $input_file["file"];
        if ($input_file["type"] == "video_note") $input_file["type"] = "videoNote";
        return $this->sendRequest("send" . $input_file["type"], $request_params);
    }

    /**
     * @param stdClass $update
     * @return int
     */
    private function getChatID(stdClass $update): int
    {
        if (!empty($update->message->chat->id)) {
            return $update->message->chat->id;
        } elseif (!empty($update->callback_query->message->chat->id)) {
            return $update->callback_query->message->chat->id;
        }
        return null;
    }

    /**
     * @param string $type
     * @param string $media
     * @param bool $is_local
     * @param array $params
     * @return array
     * @throws TelegramBotException
     */
    function generateMedia(string $type, string $media, bool $is_local, array $params = []): array
    {
        $available_types = ["photo", "video"];
        if (!in_array($type, $available_types)) throw new TelegramBotException("Invalid media type provided");
        foreach ($params as $param => $value) {
            $media_params[$param] = $value;
        }
        if (empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        $media_params["media"] = $is_local ? fopen($media, 'r') : $media;
        $media_params["type"] = $type;
        return $media_params;
    }

    /**
     * @param array $media_files
     * @param string|null $chat_id
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendMediaGroup(array $media_files, string $chat_id = null, array $params = []): stdClass
    {
        $available_types = ["photo", "video"];
        if (!is_array($media_files)) throw new TelegramBotException("Invalid files provided");
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        foreach ($media_files as $media_file) {
            if (!empty($media_file["media"]) and in_array($media_file["type"], $available_types)) $request_params["media"][] = $media_file;
        }
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["media"] = json_encode($request_params["media"]);
        return $this->sendRequest("sendMediaGroup", $request_params);
    }

    /**
     * @param string $type
     * @param float $latitude
     * @param float $longitude
     * @param string|null $chat_id
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendPosition(string $type, float $latitude, float $longitude, string $chat_id = null, array $params = []): stdClass
    {
        $available_types = ["location", "venue"];
        if (!in_array($type, $available_types)) throw new TelegramBotException("Invalid position type specified");
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        if ($type == "venue" and (empty($params["title"]) or empty($params["address"]))) throw new TelegramBotException("Missing required parameters for Venue");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["latitude"] = $latitude;
        $request_params["longitude"] = $longitude;
        return $this->sendRequest("sendLocation", $request_params);
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param bool $is_inline
     * @param int $message_id
     * @param string|null $chat_id
     * @param string|null $reply_markup
     * @return stdClass
     * @throws TelegramBotException
     */
    function editMessageLiveLocation(float $latitude, float $longitude, bool $is_inline, int $message_id, string $chat_id = null, string $reply_markup = null): stdClass
    {
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) throw new TelegramBotException("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        $request_params["latitude"] = $latitude;
        $request_params["longitude"] = $longitude;
        if (!empty($reply_markup)) $request_params["reply_markup"] = $reply_markup;
        return $this->sendRequest("editMessageLiveLocation", $request_params);
    }

    /**
     * @param bool $is_inline
     * @param string $message_id
     * @param string|null $chat_id
     * @param string|null $reply_markup
     * @return stdClass
     * @throws TelegramBotException
     */
    function stopMessageLiveLocation(bool $is_inline, string $message_id, string $chat_id = null, string $reply_markup = null): stdClass
    {
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) throw new TelegramBotException("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        if (!empty($reply_markup)) $request_params["reply_markup"] = $reply_markup;
        return $this->sendRequest("stopMessageLiveLocation", $request_params);
    }

    /**
     * @param string $phone_number
     * @param string $first_name
     * @param string|null $chat_id
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendContact(string $phone_number, string $first_name, string $chat_id = null, array $params = []): stdClass
    {
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["phone_number"] = $phone_number;
        $request_params["first_name"] = $first_name;
        return $this->sendRequest("sendContact", $request_params);
    }

    /**
     * @param string $action
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendChatAction(string $action, string $chat_id = null): stdClass
    {
        $available_actions = ["typing", "upload_photo", "record_video", "upload_video", "record_audio", "upload_audio", "upload_document", "find_location", "record_video_note", "upload_video_note"];
        if (!in_array($action, $available_actions)) throw new TelegramBotException("Invalid action specified");
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["action"] = $action;
        return $this->sendRequest("sendChatAction", $request_params);
    }

    /**
     * @param int $user_id
     * @param array $params
     * @return stdClass
     */
    function getUserProfilePhotos(int $user_id, array $params = []): stdClass
    {
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("getUserProfilePhotos", $request_params);
    }

    /**
     * @param string $file_id
     * @return stdClass
     */
    function getFile(string $file_id): stdClass
    {
        $request_params["file_id"] = $file_id;
        return $this->sendRequest("getFile", $request_params);
    }

    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @param int|null $until_date
     * @return stdClass
     * @throws TelegramBotException
     */
    function kickChatMember(int $user_id, string $chat_id = null, int $until_date = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        if (!empty($until_date)) $request_params["until_date"] = $until_date;
        return $this->sendRequest("kickChatMember", $request_params);
    }

    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function unbanChatMember(int $user_id, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("unbanChatMember", $request_params);
    }

    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function restrictChatMember(int $user_id, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("restrictChatMember", $request_params);
    }

    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function promoteChatMember(int $user_id, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("promoteChatMember", $request_params);
    }

    /**
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function exportChatInviteLink(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("exportChatInviteLink", $request_params);
    }

    /**
     * @param array $input_file
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function setChatPhoto(array $input_file, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        if ($input_file["type"] != "photo" or empty($input_file["file"] or empty($input_file["is_local"]))) throw new TelegramBotException("Invalid input photo");
        if ($input_file["is_local"]) {
            $request_params["photo"] = fopen($input_file["file"], 'r');
            $response = $this->sendRequest("setChatPhoto", $request_params);
            return $response;
        } else {
            $request_params["photo"] = $input_file["file"];
            return $this->sendRequest("setChatPhoto", $request_params);
        }
    }

    /**
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function deleteChatPhoto(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("deleteChatPhoto", $request_params);
    }

    /**
     * @param string $title
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function setChatTitle(string $title, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["title"] = $title;
        return $this->sendRequest("setChatTitle", $request_params);
    }

    /**
     * @param string|null $description
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function setChatDescription(string $description = null, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        if (!empty($description)) $request_params["description"] = $description;
        return $this->sendRequest("setChatDescription", $request_params);
    }

    /**
     * @param int $message_id
     * @param string|null $chat_id
     * @param bool|null $disable_notification
     * @return stdClass
     * @throws TelegramBotException
     */
    function pinChatMessage(int $message_id, string $chat_id = null, bool $disable_notification = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["message_id"] = $message_id;
        if (!empty($disable_notification)) $request_params["disable_notification"] = $disable_notification;
        return $this->sendRequest("pinChatMessage", $request_params);
    }

    /**
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function unpinChatMessage(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("unpinChatMessage", $request_params);
    }

    /**
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function leaveChat(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("leaveChat", $request_params);
    }

    /**
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function getChat(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChat", $request_params);
    }

    /**
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function getChatAdministrators(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChatAdministrators", $request_params);
    }

    /**
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function getChatMembersCount(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChatMembersCount", $request_params);
    }

    /**
     * @param int $user_id
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function getChatMember(int $user_id, string $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("getChatMember", $request_params);
    }

    /**
     * @param string $sticker_set_name
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function setChatStickerSet(string $sticker_set_name, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["sticker_set_name"] = $sticker_set_name;
        return $this->sendRequest("setChatStickerSet", $request_params);
    }

    /**
     * @param int|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function deleteChatStickerSet(int $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("deleteChatStickerSet", $request_params);
    }

    /**
     * @param array $params
     * @param int|null $callback_query_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function answerCallbackQuery(array $params = [], int $callback_query_id = null): stdClass
    {
        if (empty($callback_query_id)) $callback_query_id = $this->getCallbackQueryID($this->update);
        if (empty($callback_query_id)) throw new TelegramBotException("Callback query ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["callback_query_id"] = $callback_query_id;
        return $this->sendRequest("answerCallbackQuery", $request_params);
    }

    /**
     * @param stdClass $update
     * @return int
     */
    private function getCallbackQueryID(stdClass $update): int
    {
        if (!empty($update->callback_query->id)) return $update->callback_query->id;
        return null;
    }

    /**
     * @param string $mode
     * @param bool $is_inline
     * @param int $message_id
     * @param string|null $chat_id
     * @param array|null $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function editMessage(string $mode, bool $is_inline, int $message_id, string $chat_id = null, array $params = null): stdClass
    {
        $available_modes = ["text", "caption", "replymarkup"];
        if (!in_array($mode, $available_modes)) throw new TelegramBotException("Invalid mode specified");
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) throw new TelegramBotException("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        if ($mode != "replymarkup" and empty($params["text"])) throw new TelegramBotException("Text required in non-replymarkup mode");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if ($mode != "replymarkup" and empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        return $this->sendRequest("editMessage" . $mode, $request_params);
    }

    /**
     * @param int $message_id
     * @param int|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function deleteMessage(int $message_id, int $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["message_id"] = $message_id;
        return $this->sendRequest("deleteMessage", $request_params);
    }

    /**
     * @param string $name
     * @return stdClass
     */
    function getStickerSet(string $name): stdClass
    {
        $request_params["name"] = $name;
        return $this->sendRequest("getStickerSet", $request_params);
    }

    /**
     * @param int $user_id
     * @param array $input_file
     * @return stdClass
     * @throws TelegramBotException
     */
    function uploadStickerFile(int $user_id, array $input_file): stdClass
    {
        $request_params["user_id"] = $user_id;
        if ($input_file["type"] != "sticker" or empty($input_file["file"] or empty($input_file["is_local"]))) throw new TelegramBotException("Invalid input photo");
        if ($input_file["is_local"]) {
            $request_params["png_sticker"] = fopen($input_file["file"], 'r');
            $response = $this->sendRequest("uploadStickerFile", $request_params);
            return $response;
        }
        $request_params["png_sticker"] = $input_file["file"];
        return $this->sendRequest("uploadStickerFile", $request_params);
    }

    /**
     * @param int $user_id
     * @param string $name
     * @param string $title
     * @param array $input_file
     * @param string $emojis
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function createNewStickerSet(int $user_id, string $name, string $title, array $input_file, string $emojis, array $params = []): stdClass
    {
        $request_params["user_id"] = $user_id;
        $request_params["name"] = $name;
        $request_params["title"] = $title;
        $request_params["emojis"] = $emojis;
        if ($input_file["type"] != "sticker" or empty($input_file["file"] or empty($input_file["is_local"]))) throw new TelegramBotException("Invalid input photo");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if ($input_file["is_local"]) {
            $request_params["png_sticker"] = fopen($input_file["file"], 'r');
            $response = $this->sendRequest("createNewStickerSet", $request_params);
            return $response;
        }
        $request_params["png_sticker"] = $input_file["file"];
        return $this->sendRequest("createNewStickerSet", $request_params);
    }

    /**
     * @param int $user_id
     * @param string $name
     * @param array $input_file
     * @param string $emojis
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function addStickerToSet(int $user_id, string $name, array $input_file, string $emojis, array $params = []): stdClass
    {
        $request_params["user_id"] = $user_id;
        $request_params["name"] = $name;
        $request_params["emojis"] = $emojis;
        if ($input_file["type"] != "sticker" or empty($input_file["file"] or empty($input_file["is_local"]))) throw new TelegramBotException("Invalid input photo");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if ($input_file["is_local"]) {
            $request_params["png_sticker"] = fopen($input_file["file"], 'r');
            $response = $this->sendRequest("createNewStickerSet", $request_params);
            return $response;
        }
        $request_params["png_sticker"] = $input_file["file"];
        return $this->sendRequest("createNewStickerSet", $request_params);
    }

    /**
     * @param string $sticker
     * @param int $position
     * @return stdClass
     */
    function setStickerPositionInSet(string $sticker, int $position): stdClass
    {
        $request_params["sticker"] = $sticker;
        $request_params["position"] = $position;
        return $this->sendRequest("setStickerPositionInSet", $request_params);
    }

    /**
     * @param string $sticker
     * @return stdClass
     */
    function deleteStickerFromSet(string $sticker): stdClass
    {
        $request_params["sticker"] = $sticker;
        return $this->sendRequest("deleteStickerFromSet", $request_params);
    }

    /**
     * @param string $type
     * @param int $id
     * @param string $title
     * @param array $params
     * @return array
     * @throws TelegramBotException
     */
    function generateInlineQueryResult(string $type, int $id, string $title, array $params): array
    {
        $available_types = ["article", "photo", "gif", "mpeg4_gif", "video", "audio", "voice", "document", "location", "venue", "contact", "game", "sticker"];
        if (!in_array($type, $available_types)) throw new TelegramBotException("Invalid InlineQueryResult type");
        $result["type"] = $type;
        $result["id"] = $id;
        $result["title"] = $title;
        foreach ($params as $param => $value) {
            $result[$param] = $value;
        }
        return $result;
    }

    /**
     * @param array $results
     * @param array $params
     * @param int|null $inline_query_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function answerInlineQuery(array $results, array $params = [], int $inline_query_id = null): stdClass
    {
        if (empty($inline_query_id)) $inline_query_id = $this->getInlineQueryID($this->update);
        if (empty($inline_query_id)) throw new TelegramBotException("Inline Query ID required");
        $request_params["inline_query_id"] = $inline_query_id;
        $request_params["results"] = json_encode($results);
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        return $this->sendRequest("answerInlineQuery", $request_params);
    }

    /**
     * @param stdClass $update
     * @return int
     */
    private function getInlineQueryID(stdClass $update): int
    {
        if (!empty($update->inline_query->id)) return $update->inline_query->id;
        return null;
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
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendInvoice(string $title, string $description, string $payload, string $provider_token, string $start_parameter, string $currency, array $prices, array $params = [], string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        $request_params = [
            "title" => $title,
            "description" => $description,
            "payload" => $payload,
            "provider_token" => $provider_token,
            "start_parameter" => $start_parameter,
            "currency" => $currency,
            "prices" => $prices
        ];
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        return $this->sendRequest("sendInvoice", $request_params);
    }

    /**
     * @param bool $ok
     * @param array $params
     * @param int|null $shipping_query_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function answerShippingQuery(bool $ok, array $params = [], int $shipping_query_id = null): stdClass
    {
        if (empty($shipping_query_id)) $shipping_query_id = $this->getShippingQueryID($this->update);
        if (empty($shipping_query_id)) throw new TelegramBotException("Shipping Query ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["ok"] = $ok;
        if ($ok) {
            if (empty($request_params["shipping_options"])) throw new TelegramBotException("Shipping Options field required");
            return $this->sendRequest("answerShippingQuery", $request_params);
        }
        if (empty($request_params["error_message"])) throw new TelegramBotException("Error message field required");
        return $this->sendRequest("answerShippingQuery", $request_params);
    }

    /**
     * @param stdClass $update
     * @return int
     */
    private function getShippingQueryID(stdClass $update): int
    {
        if (!empty($update->shipping_query->id)) return $update->shipping_query->id;
        return null;
    }

    /**
     * @param bool $ok
     * @param string|null $error_message
     * @param int|null $pre_checkout_query_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function answerPreCheckoutQuery(bool $ok, string $error_message = null, int $pre_checkout_query_id = null): stdClass
    {
        if (empty($pre_checkout_query_id)) $pre_checkout_query_id = $this->getPreCheckoutQueryID($this->update);
        if (empty($pre_checkout_query_id)) throw new TelegramBotException("Pre-checkout Query ID required");
        $request_params = [
            "pre_checkout_query_id" => $pre_checkout_query_id,
            "ok" => $ok
        ];
        if (!$ok) {
            if (empty($error_message)) throw new TelegramBotException("Error Message field required");
            $request_params["error_message"] = $error_message;
        }
        return $this->sendRequest("answerPreCheckoutQuery", $request_params);
    }

    /**
     * @param stdClass $update
     * @return int
     */
    private function getPreCheckoutQueryID(stdClass $update): int
    {
        if (!empty($update->pre_checkout_query->id)) return $update->pre_checkout_query->id;
        return null;
    }

    /**
     * @param int $user_id
     * @param array $errors
     * @return stdClass
     */
    function setPassportDataErrors(int $user_id, array $errors): stdClass
    {
        $request_params["user_id"] = $user_id;
        $request_params["errors"] = $errors;
        return $this->sendRequest("setPassportDataErrors", $request_params);
    }


    /**
     * @param string $game_short_name
     * @param array $params
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendGame(string $game_short_name, array $params = [], string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["game_short_name"] = $game_short_name;
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("sendGame", $request_params);
    }

    /**
     * @param int $user_id
     * @param int $score
     * @param bool $is_inline
     * @param int $message_id
     * @param array $params
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function setGameScore(int $user_id, int $score, bool $is_inline, int $message_id, array $params = [], string $chat_id = null): stdClass
    {
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) throw new TelegramBotException("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["score"] = $score;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("setGameScore", $request_params);
    }

    /**
     * @param int $user_id
     * @param bool $is_inline
     * @param int $message_id
     * @param string|null $chat_id
     * @return stdClass
     * @throws TelegramBotException
     */
    function getGameHighScores(int $user_id, bool $is_inline, int $message_id, string $chat_id = null): stdClass
    {
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) throw new TelegramBotException("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("getGameHighScores", $request_params);
    }

    /**
     * @throws TelegramBotException
     */
    function start(): void
    {
        if ($this->use_polling) {
            $offset = 0;
            foreach ($this->settings->general->admins as $admin) $this->sendMessage("Bot started correctly.", $admin);
            while (true) {
                $response = $this->getUpdates(['offset' => $offset]);
                if (null == $response->result) continue;
                foreach ($response->result as $update) {
                    $offset = $update->update_id;
                    if ($this->settings->maintenance->enabled and $update->message->chat->type == "private") {
                        $this->sendMessage($this->settings->maintenance->message);
                        continue;
                    }
                    if ($update->message->text == "/halt") {
                        $this->sendMessage("Shutting down...");
                        $offset++;
                        $this->getUpdates(['offset' => $offset,
                            'limit' => 1
                        ]);
                        exit;
                    }
                    $this->processUpdate($update);
                    $offset++;
                }
            }
        } else {
            $this->processUpdate($this->update);
        }
        return;
    }

    /**
     * @param string $text
     * @param string|null $chat_id
     * @param array $params
     * @return stdClass
     * @throws TelegramBotException
     */
    function sendMessage(string $text, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) throw new TelegramBotException("Chat ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if (empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        $request_params["chat_id"] = $chat_id;
        $request_params["text"] = $text;
        return $this->sendRequest("sendMessage", $request_params);
    }

    /**
     * @param array $params
     * @return stdClass
     */
    function getUpdates(array $params = []): stdClass
    {
        $request_params = [];
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        return $this->sendRequest("getUpdates", $request_params);
    }

    /**
     * @param stdClass $update
     */
    private function processUpdate(stdClass $update): void
    {
        $this->update = $update;
        $first_types = ["message", "edited_message", "channel_post", "edited_channel_post", "inline_query", "chosen_inline_result", "callback_query"];
        $second_types = ["text", "caption", "query", "data"];
        $first_field = null;
        $second_field = null;
        foreach ($first_types as $first_type) {
            if (!empty($update->$first_type)) {
                $first_field = $first_type;
                break;
            }
        }
        foreach ($second_types as $second_type) {
            if (!empty($update->$first_field->$second_type)) {
                $second_field = $second_type;
                break;
            }
        }
        $command_text = $update->$first_field->$second_field;
        foreach ($this->hooks["$first_field::$second_field"][$command_text] as $action) {
            $action($this);
        }
        return;
    }
}