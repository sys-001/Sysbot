<?php

require_once "src/core.php";

define("TOKEN", "123456:BOT_TOKEN_HERE");
define("SETTINGS_PATH", "data/management/bot.settings");

$Bot = new TelegramBot(TOKEN, SETTINGS_PATH);

// will be implemented soon
/*if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$allowed_addresses = [];
for ($i = 197; $i <= 233; $i++) {
    $allowed_addresses[] = "149.154.167.$i";
}
if (!in_array($ip, $allowed_addresses)) {
    if (hash("sha512", $_GET["administration"]) == $Bot->settings->general->administration_password) {
        echo "Future implementation.";
    } else {
        echo "Unauthorized.";
        exit;
    }
}*/

$Bot->setHook("message::text", "/start", function ($Bot) {
    $Bot->sendMessage("Hi!");
});

$Bot->setHook("message::text", "/r_kb", function ($Bot) {
    $keyboard[] = $Bot->generateReplyKeyboardRow([
        $Bot->generateReplyKeyboardButton("Contact button", true),
        $Bot->generateReplyKeyboardButton("Location button", false, true)
    ]);
    $keyboard[] = $Bot->generateReplyKeyboardRow([$Bot->generateReplyKeyboardButton("Normal button")]);
    $Bot->sendMessage("Hi with reply keyboard!", null, ["reply_markup" => $Bot->generateReplyKeyboard($keyboard)]);
});

$Bot->setHook("message::text", "/r_clear", function ($Bot) {
    $Bot->sendMessage("Hi with reply keyboard removed!", null, ["reply_markup" => $Bot->generateReplyKeyboardRemove()]);
});

$Bot->setHook("message::text", "/r_force", function ($Bot) {
    $Bot->sendMessage("Hi with force reply!", null, ["reply_markup" => $Bot->generateForceReply()]);
});

$Bot->setHook("message::text", "/i_kb", function ($Bot) {
    $keyboard[] = $Bot->generateInlineKeyboardRow([
        $Bot->generateInlineKeyboardButton("Callback button", "callback_data", "callback_button"),
        $Bot->generateInlineKeyboardButton("URL button", "url", "https://t.me/sys001")
    ]);
    $keyboard[] = $Bot->generateInlineKeyboardRow([$Bot->generateInlineKeyboardButton("Another callback button", "callback_data", "another_callback_button")]);
    $Bot->sendMessage("Hi with inline keyboard!", null, ["reply_markup" => $Bot->generateInlineKeyboard($keyboard)]);
});

$Bot->setHook("callback_query::data", "callback_button", function ($Bot) {
    $Bot->answerCallbackQuery(["text" => "Hi from toast!"]);
});

$Bot->setHook("callback_query::data", "another_callback_button", function ($Bot) {
    $Bot->answerCallbackQuery(["text" => "Hi from alert!", "show_alert" => true]);
});

$Bot->start();