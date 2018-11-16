<?php


namespace TelegramBot\Util;


use TelegramBot\Exception\TelegramBotException;
use TelegramBot\Telegram\Types\ForceReply;
use TelegramBot\Telegram\Types\InlineKeyboardButton;
use TelegramBot\Telegram\Types\InlineKeyboardMarkup;
use TelegramBot\Telegram\Types\KeyboardButton;
use TelegramBot\Telegram\Types\ReplyKeyboardMarkup;
use TelegramBot\Telegram\Types\ReplyKeyboardRemove;

/**
 * Trait KeyboardUtil
 * @package TelegramBot\Util
 */
trait KeyboardUtil
{

    /**
     * @param string $text
     * @param bool $request_contact
     * @param bool $request_location
     * @return KeyboardButton
     */
    public static function generateReplyKeyboardButton(string $text, bool $request_contact = false, bool $request_location = false): KeyboardButton
    {
        $raw_button = (object)[
            'text' => $text,
            'request_contact' => $request_contact,
            'request_location' => $request_location
        ];
        return KeyboardButton::parseKeyboardButton($raw_button);
    }

    /**
     * @param array $buttons
     * @return array
     */
    public static function generateReplyKeyboardRow(array $buttons): array
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
     * @return ReplyKeyboardMarkup
     */
    public static function generateReplyKeyboard(array $rows, array $params = []): ReplyKeyboardMarkup
    {
        $keyboard = [];
        foreach ($params as $param => $value) {
            $keyboard[$param] = $value;
        }
        $keyboard_buttons = [];
        foreach ($rows as $row) {
            $keyboard_buttons[] = $row;
        }
        $keyboard['keyboard'] = $keyboard_buttons;
        $keyboard = (object)$keyboard;
        return ReplyKeyboardMarkup::parseReplyKeyboardMarkup($keyboard);
    }

    /**
     * @param bool $remove_keyboard
     * @param bool $selective
     * @return ReplyKeyboardRemove
     */
    public static function generateReplyKeyboardRemove(bool $remove_keyboard = true, bool $selective = false): ReplyKeyboardRemove
    {
        $raw_markup = (object)[
            'remove_keyboard' => $remove_keyboard,
            'selective' => $selective
        ];
        return ReplyKeyboardRemove::parseReplyKeyboardRemove($raw_markup);
    }

    /**
     * @param bool $force_reply
     * @param bool $selective
     * @return ForceReply
     */
    public static function generateForceReply(bool $force_reply = true, bool $selective = false): ForceReply
    {
        $raw_markup = (object)[
            'force_reply' => $force_reply,
            'selective' => $selective
        ];
        return ForceReply::parseForceReply($raw_markup);
    }

    /**
     * @param string $text
     * @param string $callback_action
     * @param string $callback_content
     * @return InlineKeyboardButton
     * @throws TelegramBotException
     */
    public static function generateInlineKeyboardButton(string $text, string $callback_action, string $callback_content): InlineKeyboardButton
    {
        $allowed_actions = [
            'url',
            'callback_data',
            'switch_inline_query',
            'switch_inline_query_current_chat',
            'callback_game',
            'pay'
        ];
        if (!in_array($callback_action, $allowed_actions)) {
            throw new TelegramBotException('Invalid callback action provided');
        }
        $raw_button = (object)[
            'text' => $text,
            $callback_action => $callback_content
        ];
        return InlineKeyboardButton::parseInlineKeyboardButton($raw_button);
    }


    /**
     * @param array $buttons
     * @return array
     */
    public static function generateInlineKeyboardRow(array $buttons): array
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        return $row;
    }


    /**
     * @param array $rows
     * @return InlineKeyboardMarkup
     */
    public static function generateInlineKeyboard(array $rows): InlineKeyboardMarkup
    {
        $keyboard = [];
        foreach ($rows as $row) {
            $keyboard[] = $row;
        }
        $keyboard = (object)['inline_keyboard' => $keyboard];
        return InlineKeyboardMarkup::parseInlineKeyboardMarkup($keyboard);
    }
}