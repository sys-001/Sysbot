<?php


namespace TelegramBot\Event;


/**
 * Class ChannelPostCaptionEvent
 * @package TelegramBot\Event
 */
class ChannelPostCaptionEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['channel_post', 'caption'];

}
