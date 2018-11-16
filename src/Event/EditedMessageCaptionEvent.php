<?php


namespace TelegramBot\Event;


/**
 * Class EditedMessageCaptionEvent
 * @package TelegramBot\Event
 */
class EditedMessageCaptionEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['edited_message', 'caption'];

}
