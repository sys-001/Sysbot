<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ReplyKeyboardMarkup
 * @package TelegramBot\Telegram\Types
 */
class ReplyKeyboardMarkup implements ReplyMarkupInterface
{

    /**
     * @var
     */
    public $keyboard;
    /**
     * @var
     */
    public $resize_keyboard;
    /**
     * @var
     */
    public $one_time_keyboard;
    /**
     * @var
     */
    public $selective;


    /**
     * @param null|\stdClass $reply_keyboard_markup
     * @return null|ReplyKeyboardMarkup
     */
    public static function parseReplyKeyboardMarkup(?\stdClass $reply_keyboard_markup): ?self
    {
        if (is_null($reply_keyboard_markup)) return null;
        return (new self())
            ->setKeyboard($reply_keyboard_markup->keyboard ?? null)
            ->setResizeKeyboard($reply_keyboard_markup->resize_keyboard ?? false)
            ->setOneTimeKeyboard($reply_keyboard_markup->one_time_keyboard ?? false)
            ->setSelective($reply_keyboard_markup->selective ?? false);
    }

    /**
     * @param bool|null $selective
     * @return ReplyKeyboardMarkup
     */
    public function setSelective(?bool $selective): self
    {
        $this->selective = $selective;
        return $this;
    }

    /**
     * @param bool|null $one_time_keyboard
     * @return ReplyKeyboardMarkup
     */
    public function setOneTimeKeyboard(?bool $one_time_keyboard): self
    {
        $this->one_time_keyboard = $one_time_keyboard;
        return $this;
    }

    /**
     * @param bool|null $resize_keyboard
     * @return ReplyKeyboardMarkup
     */
    public function setResizeKeyboard(?bool $resize_keyboard): self
    {
        $this->resize_keyboard = $resize_keyboard;
        return $this;
    }

    /**
     * @param array|null $keyboard
     * @return ReplyKeyboardMarkup
     */
    public function setKeyboard(?array $keyboard): self
    {
        $this->keyboard = $keyboard;
        return $this;
    }

}