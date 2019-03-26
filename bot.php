<?php /** @noinspection PhpUnhandledExceptionInspection */

require_once __DIR__ . '/bootstrap.php';

/**
 * Bot token
 */
define('TOKEN', '123456:BOT_TOKEN');
/**
 * Settings path, can be relative or absolute
 */
define('SETTINGS_PATH', 'config/settings.json');
/**
 * Log verbosity:
 * 0 - Only Errors
 * 1 - Errors and Warnings
 * 2 - Almost everything (updates and responses included)
 */
define('LOG_VERBOSITY', 1);

use TelegramBot\{Event, Telegram\Types\InputFile, TelegramBot, Util\KeyboardUtil};

$bot = new TelegramBot(TOKEN, $entity_manager, SETTINGS_PATH, LOG_VERBOSITY);
$settings_provider = $bot->getProvider();

$bot->createSimpleEvent(['message', 'text'], '/start', function (TelegramBot $bot) {
    $bot->sendMessage($bot->getLanguageValue('greetings'));
    $photo = new InputFile('logo.png', true);
    $bot->sendPhoto($photo);
});

$bot->createSimpleEvent(['message', 'text'], '/r_kb', function (TelegramBot $bot) {
    $keyboard[] = KeyboardUtil::generateReplyKeyboardRow([
        KeyboardUtil::generateReplyKeyboardButton($bot->getLanguageValue('contact_btn'), true),
        KeyboardUtil::generateReplyKeyboardButton($bot->getLanguageValue('location_btn'), false, true)
    ]);
    $keyboard[] = KeyboardUtil::generateReplyKeyboardRow([KeyboardUtil::generateReplyKeyboardButton($bot->getLanguageValue('normal_btn'))]);
    $bot->sendMessage($bot->getLanguageValue('greetings_kb'), null,
        ['reply_markup' => KeyboardUtil::generateReplyKeyboard($keyboard)]);
});

$trigger = new Event\Trigger('/r_clear', true, false);
$event = new Event\MessageTextEvent($trigger, function (TelegramBot $bot) {
    $bot->sendMessage($bot->getLanguageValue('greetings_remove_kb'), null,
        ['reply_markup' => KeyboardUtil::generateReplyKeyboardRemove()]);
});
$bot->addEvent($event);

$bot->createSimpleEvent(['message', 'text'], '/r_force', function (TelegramBot $bot) {
    $bot->sendMessage($bot->getLanguageValue('greetings_force'), null,
        ['reply_markup' => KeyboardUtil::generateForceReply()]);
});

$bot->createSimpleEvent(['message', 'text'], '/i_kb', function (TelegramBot $bot) {
    $keyboard[] = KeyboardUtil::generateInlineKeyboardRow([
        KeyboardUtil::generateInlineKeyboardButton($bot->getLanguageValue('callback_btn'), 'callback_data',
            'callback_button'),
        KeyboardUtil::generateInlineKeyboardButton($bot->getLanguageValue('url_btn'), 'url', 'https://t.me/sys001')
    ]);
    $keyboard[] = KeyboardUtil::generateInlineKeyboardRow([
        KeyboardUtil::generateInlineKeyboardButton($bot->getLanguageValue('another_callback_btn'), 'callback_data',
            'another_callback_button')
    ]);
    $bot->sendMessage($bot->getLanguageValue('greetings_inline'), null,
        ['reply_markup' => KeyboardUtil::generateInlineKeyboard($keyboard)]);
});

$bot->createSimpleEvent(['callback_query', 'data'], 'callback_button', function (TelegramBot $bot) {
    $bot->answerCallbackQuery(['text' => $bot->getLanguageValue('greetings_toast')]);
});

$bot->createSimpleEvent(['callback_query', 'data'], 'another_callback_button', function (TelegramBot $bot) {
    $bot->answerCallbackQuery(['text' => $bot->getLanguageValue('greetings_alert'), 'show_alert' => true]);
});

$bot->start();