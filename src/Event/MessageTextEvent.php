<?php


namespace TelegramBot\Event;


/**
 * Class MessageTextEvent
 * @package TelegramBot\Event
 */
class MessageTextEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['message', 'text'];
}
