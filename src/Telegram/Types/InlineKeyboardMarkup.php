<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineKeyboardMarkup
 * @package TelegramBot\Telegram\Types
 */
class InlineKeyboardMarkup implements ReplyMarkupInterface
{

    /**
     * @var
     */
    public $inline_keyboard;


    /**
     * @param null|\stdClass $inline_keyboard_markup
     * @return null|InlineKeyboardMarkup
     */
    public static function parseInlineKeyboardMarkup(?\stdClass $inline_keyboard_markup): ?self
    {
        if (is_null($inline_keyboard_markup)) {
            return null;
        }
        return (new self())
            ->setInlineKeyboard($inline_keyboard_markup->inline_keyboard ?? null);
    }

    /**
     * @param array|null $inline_keyboard
     * @return InlineKeyboardMarkup
     */
    public function setInlineKeyboard(?array $inline_keyboard): self
    {
        $this->inline_keyboard = $inline_keyboard;
        return $this;
    }

}