<?php

require_once "settingsProvider.php";

class TelegramBot
{
    const ENDPOINT = "https://api.telegram.org/";
    private $token = null;
    private $curl_handle = null;
    private $provider = null;
    public $settings = null;
    public $update = null;
    public $use_polling = false;

    /**------------------**/
    /**internal functions**/
    /**------------------**/

    function refreshHandle()
    {
        curl_close($this->curl_handle);
        $this->curl_handle = curl_init();
    }

    function refreshSettings($settings)
    {
        $this->provider->validateSettings($settings);
        $this->settings = $settings;
        $this->provider->saveSettings($settings);
    }

    private function getChatID($update)
    {
        if (!empty($update->message->chat->id)) {
            return $update->message->chat->id;
        } elseif (!empty($update->callback_query->message->chat->id)) {
            return $update->callback_query->message->chat->id;
        } else {
            return null;
        }
    }

    /**------------------**/

    function sendRequest($method, $params)
    {
        curl_setopt_array($this->curl_handle, [CURLOPT_URL => self::ENDPOINT . $this->token . '/' . $method,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTPHEADER => ["Connection: Keep-Alive", "Keep-Alive: 120"]
        ]);
        $response = curl_exec($this->curl_handle);
        return json_decode($response);
    }

    function getMe()
    {
        return $this->sendRequest("getMe", null);
    }

    function getUpdates($params = null)
    {
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        return $this->sendRequest("getUpdates", $request_params);
    }

    function setWebhook($url, $params = null)
    {
        $request_params["url"] = $url;
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        if (!empty($request_params["certificate"])) {
            $request_params["certificate"] = curl_file_create(realpath($request_params["certificate"]));
        }
        $response = $this->sendRequest("setWebhook", $request_params);
        curl_setopt($this->curl_handle, CURLOPT_HEADER, false);
        return $response;
    }

    function deleteWebhook()
    {
        return $this->sendRequest("deleteWebhook", null);
    }

    function getWebhookInfo()
    {
        return $this->sendRequest("getWebhookInfo", null);
    }

    function generateReplyKeyboardButton($text, $request_contact = false, $request_location = false){
        return ["text" => $text,
        "request_contact" => $request_contact,
        "request_location" => $request_location
        ];
    }

    function generateReplyKeyboardRow($buttons)
    {
        $row = [];
        foreach($buttons as $button){
            $row[] = $button;
        }
    return $row;
    }

    function generateReplyKeyboard($rows, $params = null){
        $keyboard = [];
        foreach($params as $param => $value){
            $keyboard[$param] = $value;
        }
        $keyboard_buttons = [];
        foreach($rows as $row){
            $keyboard_buttons[] = $row;
        }
        $keyboard["keyboard"] = $keyboard_buttons;
        return json_encode($keyboard);
    }

    function generateReplyKeyboardRemove($remove_keyboard = true, $selective = false){
        return json_encode(["remove_keyboard" => $remove_keyboard,
            "selective" => $selective
        ]);
    }

    function generateForceReply($force_reply = true, $selective = false){
        return json_encode(["force_reply" => $force_reply,
            "selective" => $selective
        ]);
    }

    function generateInlineKeyboardButton($text, $callback_action, $callback_content)
    {
    $allowed_actions = ["url", "callback_data", "switch_inline_query", "switch_inline_query_current_chat", "callback_game", "pay"];
    if(!in_array($callback_action, $allowed_actions)) die("Invalid callback action provided");
    return ["text" => $text,
        $callback_action => $callback_content
    ];
    }

    function generateInlineKeyboardRow($buttons)
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        return $row;
    }

    function generateInlineKeyboard($rows)
    {
        $keyboard = [];
        foreach($rows as $row){
            $keyboard[] = $row;
        }
        return json_encode(['inline_keyboard' => $keyboard]);
    }

    function sendMessage($text, $chat_id = null, $params = null)
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

    function forwardMessage($chat_id, $from_chat_id, $message_id, $disable_notification = false)
    {
        return $this->sendRequest("forwardMessage", ["chat_id" => $chat_id,
            "from_chat_id" => $from_chat_id,
            "message_id" => $message_id,
            "disable_notification" => $disable_notification
        ]);
    }

    function generateFile($type, $file, $is_local)
    {
        $available_types = ["photo", "audio", "voice", "video", "videonote", "document", "sticker", "animation"];
        if (!in_array($type, $available_types)) die("Invalid file type provided");
        return ["type" => $type,
            "file" => $file,
            "is_local" => $is_local];
    }

    function sendFile($input_file, $chat_id = null, $params = null)
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
            $request_params[$input_file["type"]] = curl_file_create(realpath($input_file["file"]));
            $response = $this->sendRequest("send" . $input_file["type"], $request_params);
            curl_setopt($this->curl_handle, CURLOPT_HEADER, false);
            return $response;
        } else {
            $request_params[$input_file["type"]] = $input_file["file"];
            return $this->sendRequest("send" . $input_file["type"], $request_params);
        }
    }

    function generateMedia($type, $media, $is_local, $params = null)
    {
        $available_types = ["photo", "video"];
        if (!in_array($type, $available_types)) die("Invalid media type provided");
        foreach ($params as $param => $value) {
            $media_params[$param] = $value;
        }
        if (empty($request_params["parse_mode"])) $request_params["parse_mode"] = $this->settings->telegram->parse_mode;
        $media_params["media"] = $is_local ? curl_file_create(realpath($media)) : $media;
        $media_params["type"] = $type;
        return $media_params;
    }

    function sendMediaGroup($media_files, $chat_id = null, $params = null)
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
        curl_setopt($this->curl_handle, CURLOPT_HEADER, false);
        return $response;
    }

    function sendPosition($type, $latitude, $longitude, $chat_id, $params = null)
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

    function editMessageLiveLocation($latitude, $longitude, $is_inline, $message_id, $chat_id = null, $reply_markup = null)
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

    function stopMessageLiveLocation($is_inline, $message_id, $chat_id = null, $reply_markup = null)
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

    function sendContact($phone_number, $first_name, $chat_id = null, $params = null)
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

    function sendChatAction($action, $chat_id = null)
    {
        $available_actions = ["typing", "upload_photo", "record_video", "upload_video", "record_audio", "upload_audio", "upload_document", "find_location", "record_video_note", "upload_video_note"];
        if (!in_array($action, $available_actions)) die("Invalid action specified");
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["action"] = $action;
        return $this->sendRequest("sendChatAction", $request_params);
    }

    function getUserProfilePhotos($user_id, $params = null)
    {
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("getUserProfilePhotos", $request_params);
    }

    function getFile($file_id)
    {
        $request_params["file_id"] = $file_id;
        return $this->sendRequest("getFile", $request_params);
    }

    function kickChatMember($user_id, $chat_id = null, $until_date = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        if (!empty($until_date)) $request_params["until_date"] = $until_date;
        return $this->sendRequest("kickChatMember", $request_params);
    }

    function unbanChatMember($user_id, $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("unbanChatMember", $request_params);
    }

    function restrictChatMember($user_id, $chat_id = null, $params = null)
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

    function promoteChatMember($user_id, $chat_id = null, $params = null)
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

    function exportChatInviteLink($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("exportChatInviteLink", $request_params);
    }

    function setChatPhoto($input_file, $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        if ($input_file["type"] != "photo" or empty($input_file["file"] or empty($input_file["is_local"]))) die("Invalid input photo");
        if ($input_file["is_local"]) {
            curl_setopt($this->curl_handle, CURLOPT_HEADER, "Content-Type: multipart/form-data");
            $request_params["photo"] = fopen($input_file["file"], "rb");
            $response = $this->sendRequest("setChatPhoto", $request_params);
            curl_setopt($this->curl_handle, CURLOPT_HEADER, false);
            return $response;
        } else {
            $request_params["photo"] = $input_file["file"];
            return $this->sendRequest("setChatPhoto", $request_params);
        }
    }

    function deleteChatPhoto($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("deleteChatPhoto", $request_params);
    }

    function setChatTitle($title, $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["title"] = $title;
        return $this->sendRequest("setChatTitle", $request_params);
    }

    function setChatDescription($description = null, $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        if (!empty($description)) $request_params["description"] = $description;
        return $this->sendRequest("setChatDescription", $request_params);
    }

    function pinChatMessage($message_id, $chat_id = null, $disable_notification = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["message_id"] = $message_id;
        if (!empty($disable_notification)) $request_params["disable_notification"] = $disable_notification;
        return $this->sendRequest("pinChatMessage", $request_params);
    }

    function unpinChatMessage($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("unpinChatMessage", $request_params);
    }

    function leaveChat($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("leaveChat", $request_params);
    }

    function getChat($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChat", $request_params);
    }

    function getChatAdministrators($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChatAdministrators", $request_params);
    }

    function getChatMembersCount($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("getChatMembersCount", $request_params);
    }

    function getChatMember($user_id, $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["user_id"] = $user_id;
        return $this->sendRequest("getChatMember", $request_params);
    }

    function setChatStickerSet($sticker_set_name, $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["sticker_set_name"] = $sticker_set_name;
        return $this->sendRequest("setChatStickerSet", $request_params);
    }

    function deleteChatStickerSet($chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        return $this->sendRequest("deleteChatStickerSet", $request_params);
    }

    function answerCallbackQuery($callback_query_id, $params = null)
    {
        foreach ($params as $param => $value) {
            $request_params[$param] = $value;
        }
        $request_params["callback_query_id"] = $callback_query_id;
        return $this->sendRequest("answerCallbackQuery", $request_params);
    }

    function editMessage($mode, $is_inline, $message_id, $chat_id = null, $params = null)
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

    function deleteMessage($message_id, $chat_id = null)
    {
        if (empty($chat_id)) $chat_id = $this->getChatID($this->update);
        if (empty($chat_id)) die("Chat ID required");
        $request_params["chat_id"] = $chat_id;
        $request_params["message_id"] = $message_id;
        return $this->sendRequest("deleteMessage", $request_params);
    }

    function getStickerSet($name)
    {
        $request_params["name"] = $name;
        return $this->sendRequest("getStickerSet", $request_params);
    }

    function uploadStickerFile($user_id, $input_file)
    {
        $request_params["user_id"] = $user_id;
        if ($input_file["type"] != "sticker" or empty($input_file["file"] or empty($input_file["is_local"]))) die("Invalid input photo");
        if ($input_file["is_local"]) {
            curl_setopt($this->curl_handle, CURLOPT_HEADER, "Content-Type: multipart/form-data");
            $request_params["png_sticker"] = fopen($input_file["file"], "rb");
            $response = $this->sendRequest("uploadStickerFile", $request_params);
            curl_setopt($this->curl_handle, CURLOPT_HEADER, false);
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

    /**------------------**/

    function __construct(string $token, string $settings_path = "data/management/bot.settings")
    {
        $this->token = "bot" . str_replace("bot", "", $token);
        $this->provider = new settingsProvider($settings_path);
        if(file_exists($settings_path)){
            $this->settings = $this->provider->loadSettings();
        } else {
            $this->settings = $this->provider->createSettings();
            $this->provider->saveSettings($this->settings);
        }
        if ($this->settings->telegram->use_test_api) $this->token = $this->token . "/test";
        $this->curl_handle = curl_init();
        if (!$this->getMe()->ok) die("Invalid token provided");
        $raw_update = file_get_contents("php://input");
        if (empty($this->getWebhookInfo()->result->url) and empty($raw_update)) {
            $this->use_polling = true;
        } else {
            $this->update = json_decode($raw_update);
        }
    }
}