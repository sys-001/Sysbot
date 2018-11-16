<?php


namespace TelegramBot\Event;


/**
 * Class EditedChannelPostTextEvent
 * @package TelegramBot\Event
 */
class EditedChannelPostTextEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['edited_channel_post', 'text'];

}
