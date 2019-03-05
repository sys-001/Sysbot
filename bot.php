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
 * 1 - Errors and SettingsProvider operations
 * 2 - Errors, SettingsProvider and TelegramBot operations (updates and requests included)
 */
define('LOG_VERBOSITY', 1);

use TelegramBot\{Event, Telegram\Types\InputFile, TelegramBot, Util\KeyboardUtil};

$bot = new TelegramBot(TOKEN, $entity_manager, SETTINGS_PATH, LOG_VERBOSITY);
$settings_provider = $bot->getProvider();

$bot->createSimpleEvent(['message', 'text'], '/start', function (TelegramBot $bot) {
    $bot->sendMessage('Hi!');
    $photo = new InputFile('logo.png', true);
    $bot->sendPhoto($photo);
});

$bot->createSimpleEvent(['message', 'text'], '/r_kb', function (TelegramBot $bot) {
    $keyboard[] = KeyboardUtil::generateReplyKeyboardRow([
        KeyboardUtil::generateReplyKeyboardButton('Contact button', true),
        KeyboardUtil::generateReplyKeyboardButton('Location button', false, true)
    ]);
    $keyboard[] = KeyboardUtil::generateReplyKeyboardRow([KeyboardUtil::generateReplyKeyboardButton('Normal button')]);
    $bot->sendMessage('Hi with reply keyboard!', null, ['reply_markup' => KeyboardUtil::generateReplyKeyboard($keyboard)]);
});

$trigger = new Event\Trigger('/r_clear', true, false);
$event = new Event\MessageTextEvent($trigger, function (TelegramBot $bot) {
    $bot->sendMessage('Hi with reply keyboard removed!', null, ['reply_markup' => KeyboardUtil::generateReplyKeyboardRemove()]);
});
$bot->addEvent($event);

$bot->createSimpleEvent(['message', 'text'], '/r_force', function (TelegramBot $bot) {
    $bot->sendMessage('Hi with force reply!', null, ['reply_markup' => KeyboardUtil::generateForceReply()]);
});

$bot->createSimpleEvent(['message', 'text'], '/i_kb', function (TelegramBot $bot) {
    $keyboard[] = KeyboardUtil::generateInlineKeyboardRow([
        KeyboardUtil::generateInlineKeyboardButton('Callback button', 'callback_data', 'callback_button'),
        KeyboardUtil::generateInlineKeyboardButton('URL button', 'url', 'https://t.me/sys001')
    ]);
    $keyboard[] = KeyboardUtil::generateInlineKeyboardRow([KeyboardUtil::generateInlineKeyboardButton('Another callback button', 'callback_data', 'another_callback_button')]);
    $bot->sendMessage('Hi with inline keyboard!', null, ['reply_markup' => KeyboardUtil::generateInlineKeyboard($keyboard)]);
});

$bot->createSimpleEvent(['callback_query', 'data'], 'callback_button', function (TelegramBot $bot) {
    $bot->answerCallbackQuery(['text' => 'Hi from toast!']);
});

$bot->createSimpleEvent(['callback_query', 'data'], 'another_callback_button', function (TelegramBot $bot) {
    $bot->answerCallbackQuery(['text' => 'Hi from alert!', 'show_alert' => true]);
});

$bot->start();