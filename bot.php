<?php /** @noinspection PhpUnhandledExceptionInspection */

require_once "vendor/autoload.php";

define("TOKEN", "123456:BOT_TOKEN");
define("SETTINGS_PATH", "config/settings.json");
define("LOG_VERBOSITY", 1);

use TelegramBot\TelegramBot;

$bot = new TelegramBot(TOKEN, SETTINGS_PATH, LOG_VERBOSITY);
$settings_provider = $bot->getProvider();

$bot->setHook("message::text", "/start", function (TelegramBot $bot) {
    $bot->sendMessage("Hi!");
});

$bot->setHook("message::text", "/r_kb", function (TelegramBot $bot) {
    $keyboard[] = $bot->generateReplyKeyboardRow([
        $bot->generateReplyKeyboardButton("Contact button", true),
        $bot->generateReplyKeyboardButton("Location button", false, true)
    ]);
    $keyboard[] = $bot->generateReplyKeyboardRow([$bot->generateReplyKeyboardButton("Normal button")]);
    $bot->sendMessage("Hi with reply keyboard!", null, ["reply_markup" => $bot->generateReplyKeyboard($keyboard)]);
});

$bot->setHook("message::text", "/r_clear", function (TelegramBot $bot) {
    $bot->sendMessage("Hi with reply keyboard removed!", null, ["reply_markup" => $bot->generateReplyKeyboardRemove()]);
});

$bot->setHook("message::text", "/r_force", function (TelegramBot $bot) {
    $bot->sendMessage("Hi with force reply!", null, ["reply_markup" => $bot->generateForceReply()]);
});

$bot->setHook("message::text", "/i_kb", function (TelegramBot $bot) {
    $keyboard[] = $bot->generateInlineKeyboardRow([
        $bot->generateInlineKeyboardButton("Callback button", "callback_data", "callback_button"),
        $bot->generateInlineKeyboardButton("URL button", "url", "https://t.me/sys001")
    ]);
    $keyboard[] = $bot->generateInlineKeyboardRow([$bot->generateInlineKeyboardButton("Another callback button", "callback_data", "another_callback_button")]);
    $bot->sendMessage("Hi with inline keyboard!", null, ["reply_markup" => $bot->generateInlineKeyboard($keyboard)]);
});

$bot->setHook("callback_query::data", "callback_button", function (TelegramBot $bot) {
    $bot->answerCallbackQuery(["text" => "Hi from toast!"]);
});

$bot->setHook("callback_query::data", "another_callback_button", function (TelegramBot $bot) {
    $bot->answerCallbackQuery(["text" => "Hi from alert!", "show_alert" => true]);
});

$bot->start();