<?php

namespace TelegramBot\Types\Telegram;

/**
 * Class Update
 * @package TelegramBot\Types\Telegram
 */
class Update
{
    public $update_id;
    public $message;
    public $edited_message;
    public $channel_post;
    public $edited_channel_post;
    public $inline_query;
    public $chosen_inline_result;
    public $callback_query;
    public $shipping_query;
    public $pre_checkout_query;

    /**
     * Update constructor.
     * @param \stdClass $update
     */
    public function __construct(\stdClass $update)
    {
        foreach(get_object_vars($update) as $field => $value){
            $this->$field = $value;
        }
    }


}