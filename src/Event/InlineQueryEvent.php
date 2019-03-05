<?php


namespace TelegramBot\Event;


/**
 * Class InlineQueryEvent
 * @package TelegramBot\Event
 */
class InlineQueryEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\InlineQuery';
    /**
     * @var array
     */
    public static $update_path = ['inline_query', 'query'];

}
