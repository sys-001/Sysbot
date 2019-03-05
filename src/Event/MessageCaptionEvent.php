<?php


namespace TelegramBot\Event;


/**
 * Class MessageCaptionEvent
 * @package TelegramBot\Event
 */
class MessageCaptionEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['message', 'caption'];

}
