<?php


namespace TelegramBot\Event;


/**
 * Class ChannelPostTextEvent
 * @package TelegramBot\Event
 */
class ChannelPostTextEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['channel_post', 'text'];

}
