<?php


namespace TelegramBot\Event;


/**
 * Class EditedChannelPostCaptionEvent
 * @package TelegramBot\Event
 */
class EditedChannelPostCaptionEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\Message';
    /**
     * @var array
     */
    public static $update_path = ['edited_channel_post', 'caption'];

}
