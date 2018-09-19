<?php

/** @noinspection PhpIncludeInspection */
require 'vendor/autoload.php';
require_once "settingsProvider.php";

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TelegramBot
{
    const ENDPOINT = "https://api.telegram.org/";
    private $token = null;
    private $client = null;
    private $provider = null;
    private $hooks = null;
    private $settings = null;
    private $update = null;
    private $use_polling = false;

    function setHook(string $update_type, string $command, callable $action): void
    {
        $available_types = ["message::text", "message::caption", "edited_message::text", "edited_message::caption", "channel_post::text", "channel_post::caption", "edited_channel_post::text", "edited_channel_post::caption", "inline_query::query", "chosen_inline_result::query", "callback_query::data"];
        if (!in_array($update_type, $available_types)) die("Invalid update type");
        $this->hooks[$update_type][$command][] = $action;
        return;
    }

    function refreshSettings(stdClass $settings): void
    {
        $this->provider->validateSettings($settings);
        $this->settings = $settings;
        $this->provider->saveSettings($settings);
        return;
    }

    private function getChatID(stdClass $update): int
    {
        if (!empty($update->message->chat->id)) {
            return $update->message->chat->id;
        } elseif (!empty($update->callback_query->message->chat->id)) {
            return $update->callback_query->message->chat->id;
        }
        return null;
    }

    private function getCallbackQueryID(stdClass $update): int
    {
        if (!empty($update->callback_query->id)) return $update->callback_query->id;
        return null;
    }

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

    function getMe(): stdClass
    {
        return $this->sendRequest("getMe", []);
    }

    function getUpdates(array $params = []): stdClass
    {
        $request_params = [];
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        return $this->sendRequest("getUpdates", $request_params);
    }

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

    function deleteWebhook(): stdClass
    {
        return $this->sendRequest("deleteWebhook", []);
    }

    function getWebhookInfo(): stdClass
    {
        return $this->sendRequest("getWebhookInfo", []);
    }

    function generateReplyKeyboardButton(string $text, bool $request_contact = false, bool $request_location = false): array
    {
        return ["text" => $text,
            "request_contact" => $request_contact,
            "request_location" => $request_location
        ];
    }

    function generateReplyKeyboardRow(array $buttons): array
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        return $row;
    }

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

    function generateReplyKeyboardRemove(bool $remove_keyboard = true, bool $selective = false): string
    {
        return json_encode(["remove_keyboard" => $remove_keyboard,
            "selective" => $selective
        ]);
    }

    function generateForceReply(bool $force_reply = true, bool $selective = false): string
    {
        return json_encode(["force_reply" => $force_reply,
            "selective" => $selective
        ]);
    }

    function generateInlineKeyboardButton(string $text, string $callback_action, string $callback_content): array
    {
        $allowed_actions = ["url", "callback_data", "switch_inline_query", "switch_inline_query_current_chat", "callback_game", "pay"];
        if (!in_array($callback_action, $allowed_actions)) die("Invalid callback action provided");
        return ["text" => $text,
            $callback_action => $callback_content
        ];
    }

    function generateInlineKeyboardRow(array $buttons): array
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        return $row;
    }

    function generateInlineKeyboard(array $rows): string
    {
        $keyboard = [];
        foreach ($rows as $row) {
            $keyboard[] = $row;
        }
        return json_encode(['inline_keyboard' => $keyboard]);
    }

    function sendMessage(string $text, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if (empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        $request_params["chat_id"] = $chat_id;
        $request_params["text"] = $text;
        return $this->sendRequest("sendMessage", $request_params);
    }

    function forwardMessage(string $chat_id, string $from_chat_id, int $message_id, bool $disable_notification = false): stdClass
    {
        return $this->sendRequest("forwardMessage", ["chat_id" => $chat_id,
            "from_chat_id" => $from_chat_id,
            "message_id" => $message_id,
            "disable_notification" => $disable_notification
        ]);
    }

    function generateFile(string $type, string $file, bool $is_local): array
    {
        $available_types = ["photo", "audio", "voice", "video", "videonote", "document", "sticker", "animation"];
        if (!in_array($type, $available_types)) die("Invalid file type provided");
        return ["type" => $type,
            "file" => $file,
            "is_local" => $is_local];
    }

    function sendFile(array $input_file, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $available_types = ["photo", "audio", "voice", "video", "videonote", "document", "sticker", "animation"];
        if (!in_array($input_file["type"], $available_types) or empty($input_file["file"] or empty($input_file["is_local"]))) die("Invalid input file provided");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        if (empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        if ($input_file["is_local"]) {
            $request_params[$input_file["type"]] = fopen($input_file["file"], 'r');
            $response = $this->sendRequest("send" . $input_file["type"], $request_params);
            return $response;
        } else {
            $request_params[$input_file["type"]] = $input_file["file"];
            return $this->sendRequest("send" . $input_file["type"], $request_params);
        }
    }

    function generateMedia(string $type, string $media, bool $is_local, array $params = []): array
    {
        $available_types = ["photo", "video"];
        if (!in_array($type, $available_types)) die("Invalid media type provided");
        foreach ($params as $param => $value) {
            $media_params[$param] = $value;
        }
        if (empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        $media_params["media"] = $is_local ? fopen($media, 'r') : $media;
        $media_params["type"] = $type;
        return $media_params;
    }

    function sendMediaGroup(array $media_files, string $chat_id = null, array $params = []): stdClass
    {
        $available_types = ["photo", "video"];
        if (!is_array($media_files)) die("Invalid files provided");
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        foreach ($media_files as $media_file) {
            if (!empty($media_file["media"]) and in_array($media_file["type"], $available_types)) $request_params["media"][] = $media_file;
        }
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["media"] = json_encode($request_params["media"]);
        $response = $this->sendRequest("sendMediaGroup", $request_params);
        return $response;
    }

    function sendPosition(string $type, float $latitude, float $longitude, string $chat_id = null, array $params = []): stdClass
    {
        $available_types = ["location", "venue"];
        if (!in_array($type, $available_types)) die("Invalid position type specified");
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        if ($type == "venue" and (empty($params["title"]) or empty($params["address"]))) die("Missing required parameters for Venue");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["latitude"] = $latitude;
        $request_params["longitude"] = $longitude;
        return $this->sendRequest("sendLocation", $request_params);
    }

    function editMessageLiveLocation(float $latitude, float $longitude, bool $is_inline, int $message_id, string $chat_id = null, string $reply_markup = null): stdClass
    {
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) die("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        $request_params["latitude"] = $latitude;
        $request_params["longitude"] = $longitude;
        if (!empty($reply_markup)) $request_params["reply_markup"] = $reply_markup;
        return $this->sendRequest("editMessageLiveLocation", $request_params);
    }

    function stopMessageLiveLocation(bool $is_inline, string $message_id, string $chat_id = null, string $reply_markup = null): stdClass
    {
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) die("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        if (!empty($reply_markup)) $request_params["reply_markup"] = $reply_markup;
        return $this->sendRequest("stopMessageLiveLocation", $request_params);
    }

    function sendContact(string $phone_number, string $first_name, string $chat_id = null, array $params = []): stdClass
    {
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["phone_number"] = $phone_number;
        $request_params["first_name"] = $first_name;
        return $this->sendRequest("sendContact", $request_params);
    }

    function sendChatAction(string $action, string $chat_id = null): stdClass
    {
        $available_actions = ["typing", "upload_photo", "record_video", "upload_video", "record_audio", "upload_audio", "upload_document", "find_location", "record_video_note", "upload_video_note"];
        if (!in_array($action, $available_actions)) die("Invalid action specified");
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["action"] = $action;
        return $this->sendRequest("sendChatAction", $request_params);
    }

    function getUserProfilePhotos(int $user_id, array $params = []): stdClass
    {
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("getUserProfilePhotos", $request_params);
    }

    function getFile(string $file_id): stdClass
    {
        $request_params["file_id"] = $file_id;
        return $this->sendRequest("getFile", $request_params);
    }

    function kickChatMember(int $user_id, string $chat_id = null, int $until_date = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        if (!empty($until_date)) $request_params["until_date"] = $until_date;
        return $this->sendRequest("kickChatMember", $request_params);
    }

    function unbanChatMember(int $user_id, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("unbanChatMember", $request_params);
    }

    function restrictChatMember(int $user_id, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("restrictChatMember", $request_params);
    }

    function promoteChatMember(int $user_id, string $chat_id = null, array $params = []): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("promoteChatMember", $request_params);
    }

    function exportChatInviteLink(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("exportChatInviteLink", $request_params);
    }

    function setChatPhoto(array $input_file, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        if ($input_file["type"] != "photo" or empty($input_file["file"] or empty($input_file["is_local"]))) die("Invalid input photo");
        if ($input_file["is_local"]) {
            $request_params["photo"] = fopen($input_file["file"], 'r');
            $response = $this->sendRequest("setChatPhoto", $request_params);
            return $response;
        } else {
            $request_params["photo"] = $input_file["file"];
            return $this->sendRequest("setChatPhoto", $request_params);
        }
    }

    function deleteChatPhoto(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("deleteChatPhoto", $request_params);
    }

    function setChatTitle(string $title, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["title"] = $title;
        return $this->sendRequest("setChatTitle", $request_params);
    }

    function setChatDescription(string $description = null, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        if (!empty($description)) $request_params["description"] = $description;
        return $this->sendRequest("setChatDescription", $request_params);
    }

    function pinChatMessage(int $message_id, string $chat_id = null, bool $disable_notification = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["message_id"] = $message_id;
        if (!empty($disable_notification)) $request_params["disable_notification"] = $disable_notification;
        return $this->sendRequest("pinChatMessage", $request_params);
    }

    function unpinChatMessage(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("unpinChatMessage", $request_params);
    }

    function leaveChat(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("leaveChat", $request_params);
    }

    function getChat(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChat", $request_params);
    }

    function getChatAdministrators(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChatAdministrators", $request_params);
    }

    function getChatMembersCount(string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChatMembersCount", $request_params);
    }

    function getChatMember(int $user_id, string $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("getChatMember", $request_params);
    }

    function setChatStickerSet(string $sticker_set_name, string $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["sticker_set_name"] = $sticker_set_name;
        return $this->sendRequest("setChatStickerSet", $request_params);
    }

    function deleteChatStickerSet(int $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("deleteChatStickerSet", $request_params);
    }

    function answerCallbackQuery(array $params = [], int $callback_query_id = null): stdClass
    {
        if (empty($callback_query_id)) $callback_query_id = $this->getCallbackQueryID($this->update);
        if (empty($callback_query_id)) die("Callback query ID required");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["callback_query_id"] = $callback_query_id;
        return $this->sendRequest("answerCallbackQuery", $request_params);
    }

    function editMessage(string $mode, bool $is_inline, int $message_id, string $chat_id = null, array $params = null): stdClass
    {
        $available_modes = ["text", "caption", "replymarkup"];
        if (!in_array($mode, $available_modes)) die("Invalid mode specified");
        if ($is_inline) {
            $request_params["inline_message_id"] = $message_id;
        } else {
            if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
            if (empty($chat_id)) die("Chat ID required in non-inline mode");
            $request_params["chat_id"] = $chat_id;
            $request_params["message_id"] = $message_id;
        }
        if ($mode != "replymarkup" and empty($params["text"])) die("Text required in non-replymarkup mode");
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if ($mode != "replymarkup" and empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        return $this->sendRequest("editMessage" . $mode, $request_params);
    }

    function deleteMessage(int $message_id, int $chat_id = null): stdClass
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["message_id"] = $message_id;
        return $this->sendRequest("deleteMessage", $request_params);
    }

    function getStickerSet(string $name): stdClass
    {
        $request_params["name"] = $name;
        return $this->sendRequest("getStickerSet", $request_params);
    }

    function uploadStickerFile(int $user_id, array $input_file): stdClass
    {
        $request_params["user_id"] = $user_id;
        if ($input_file["type"] != "sticker" or empty($input_file["file"] or empty($input_file["is_local"]))) die("Invalid input photo");
        if ($input_file["is_local"]) {
            $request_params["png_sticker"] = fopen($input_file["file"], 'r');
            $response = $this->sendRequest("uploadStickerFile", $request_params);
            return $response;
        } else {
            $request_params["png_sticker"] = $input_file["file"];
            return $this->sendRequest("uploadStickerFile", $request_params);
        }
    }

    /**will be implemented soon**/

    function createNewStickerSet()
    {
    }

    function addStickerToSet()
    {
    }

    function setStickerPositionInSet()
    {
    }

    function deleteStickerFromSet()
    {
    }

    function answerInlineQuery()
    {
    }

    function sendInvoice()
    {
    }

    function answerShippingQuery()
    {
    }

    function answerPreCheckoutQuery()
    {
    }

    function setPassportDataErrors()
    {
    }

    function sendGame()
    {
    }

    function setGameScore()
    {
    }

    function getGameHighScores()
    {
    }

    function __construct(string $token, string $settings_path = "data/management/bot.settings")
    {
        $this->token = "bot" . str_replace("bot", "", $token);
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
        if (!$this->getMe()->ok) die("Invalid token provided");
        $raw_update = file_get_contents("php://input");
        if (empty($this->getWebhookInfo()->result->url) and empty($raw_update)) {
            $this->use_polling = true;
        } else {
            $this->update = json_decode($raw_update);
        }
    }

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

    function start(): void
    {
        if ($this->use_polling) {
            $offset = 0;
            foreach ($this->settings->general->admins as $admin) $this->sendMessage("Bot started correctly.", $admin);
            while (true) {
                $response = $this->getUpdates(['offset' => $offset]);
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
}