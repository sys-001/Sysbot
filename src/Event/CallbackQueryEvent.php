<?php


namespace TelegramBot\Event;


/**
 * Class CallbackQueryEvent
 * @package TelegramBot\Event
 */
class CallbackQueryEvent extends DefaultEvent
{
    /**
     * @var string
     */
    public static $type = 'TelegramBot\Telegram\Types\CallbackQuery';
    /**
     * @var array
     */
    public static $update_path = ['callback_query', 'data'];

}
