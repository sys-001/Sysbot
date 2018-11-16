<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ReplyKeyboardRemove
 * @package TelegramBot\Telegram\Types
 */
class ReplyKeyboardRemove implements ReplyMarkupInterface
{

    /**
     * @var
     */
    public $remove_keyboard;
    /**
     * @var
     */
    public $selective;


    /**
     * @param null|\stdClass $reply_keyboard_remove
     * @return null|ReplyKeyboardRemove
     */
    public static function parseReplyKeyboardRemove(?\stdClass $reply_keyboard_remove): ?self
    {
        if (is_null($reply_keyboard_remove)) return null;
        return (new self())
            ->setRemoveKeyboard($reply_keyboard_remove->remove_keyboard ?? true)
            ->setSelective($reply_keyboard_remove->selective ?? false);
    }

    /**
     * @param bool|null $selective
     * @return ReplyKeyboardRemove
     */
    public function setSelective(?bool $selective): self
    {
        $this->selective = $selective;
        return $this;
    }

    /**
     * @param bool|null $remove_keyboard
     * @return ReplyKeyboardRemove
     */
    public function setRemoveKeyboard(?bool $remove_keyboard): self
    {
        $this->remove_keyboard = $remove_keyboard;
        return $this;
    }

}