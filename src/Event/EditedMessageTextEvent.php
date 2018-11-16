<?php


namespace TelegramBot\Event;


/**
 * Class EditedMessageTextEvent
 * @package TelegramBot\Event
 */
class EditedMessageTextEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['edited_message', 'text'];

}
