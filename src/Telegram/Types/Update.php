<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Update
 * @package TelegramBot\Telegram\Types
 */
class Update
{


    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $update_id;
    /**
     * @var
     */
    public $message;
    /**
     * @var
     */
    public $edited_message;
    /**
     * @var
     */
    public $channel_post;
    /**
     * @var
     */
    public $edited_channel_post;
    /**
     * @var
     */
    public $inline_query;
    /**
     * @var
     */
    public $chosen_inline_result;
    /**
     * @var
     */
    public $callback_query;
    /**
     * @var
     */
    public $shipping_query;
    /**
     * @var
     */
    public $pre_checkout_query;


    /**
     * @param null|\stdClass $update
     * @return null|Update
     */
    public static function parseUpdate(?\stdClass $update): ?self
    {
        if (is_null($update)) return null;
        return (new self())
            ->setUpdateId($update->update_id ?? null)
            ->setMessage(Message::parseMessage($update->message ?? null))
            ->setEditedMessage(Message::parseMessage($update->edited_message ?? null))
            ->setChannelPost(Message::parseMessage($update->channel_post ?? null))
            ->setEditedChannelPost(Message::parseMessage($update->edited_channel_post ?? null))
            ->setInlineQuery(InlineQuery::parseInlineQuery($update->inline_query ?? null))
            ->setChosenInlineResult(ChosenInlineResult::parseChosenInlineResult($update->chosen_inline_result ?? null))
            ->setCallbackQuery(CallbackQuery::parseCallbackQuery($update->callback_query ?? null))
            ->setShippingQuery(ShippingQuery::parseShippingQuery($update->shipping_query ?? null))
            ->setPreCheckoutQuery(PreCheckoutQuery::parsePreCheckoutQuery($update->pre_checkout_query ?? null))
            ->parseUpdateType($update);
    }

    /**
     * @param \stdClass|null $update
     * @return Update
     */
    private function parseUpdateType(?\stdClass $update): self
    {
        if (false === empty($update->message)) {
            $this->type = "message";
        } elseif (false === empty($update->edited_message)) {
            $this->type = "edited_message";
        } elseif (false === empty($update->channel_post)) {
            $this->type = "channel_post";
        } elseif (false === empty($update->edited_channel_post)) {
            $this->type = "edited_channel_post";
        } elseif (false === empty($update->inline_query)) {
            $this->type = "inline_query";
        } elseif (false === empty($update->chosen_inline_result)) {
            $this->type = "chosen_inline_result";
        } elseif (false === empty($update->callback_query)) {
            $this->type = "callback_query";
        } elseif (false === empty($update->shipping_query)) {
            $this->type = "shipping_query";
        } elseif (false === empty($update->pre_checkout_query)) {
            $this->type = "pre_checkout_query";
        }
        return $this;
    }

    /**
     * @param null|PreCheckoutQuery $pre_checkout_query
     * @return Update
     */
    public function setPreCheckoutQuery(?PreCheckoutQuery $pre_checkout_query): self
    {
        $this->pre_checkout_query = $pre_checkout_query;
        return $this;
    }

    /**
     * @param null|ShippingQuery $shipping_query
     * @return Update
     */
    public function setShippingQuery(?ShippingQuery $shipping_query): self
    {
        $this->shipping_query = $shipping_query;
        return $this;
    }

    /**
     * @param null|CallbackQuery $callback_query
     * @return Update
     */
    public function setCallbackQuery(?CallbackQuery $callback_query): self
    {
        $this->callback_query = $callback_query;
        return $this;
    }

    /**
     * @param null|ChosenInlineResult $chosen_inline_result
     * @return Update
     */
    public function setChosenInlineResult(?ChosenInlineResult $chosen_inline_result): self
    {
        $this->chosen_inline_result = $chosen_inline_result;
        return $this;
    }

    /**
     * @param null|InlineQuery $inline_query
     * @return Update
     */
    public function setInlineQuery(?InlineQuery $inline_query): self
    {
        $this->inline_query = $inline_query;
        return $this;
    }

    /**
     * @param null|Message $edited_channel_post
     * @return Update
     */
    public function setEditedChannelPost(?Message $edited_channel_post): self
    {
        $this->edited_channel_post = $edited_channel_post;
        return $this;
    }

    /**
     * @param null|Message $channel_post
     * @return Update
     */
    public function setChannelPost(?Message $channel_post): self
    {
        $this->channel_post = $channel_post;
        return $this;
    }

    /**
     * @param null|Message $edited_message
     * @return Update
     */
    public function setEditedMessage(?Message $edited_message): self
    {
        $this->edited_message = $edited_message;
        return $this;
    }

    /**
     * @param null|Message $message
     * @return Update
     */
    public function setMessage(?Message $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param int|null $update_id
     * @return Update
     */
    public function setUpdateId(?int $update_id): self
    {
        $this->update_id = $update_id;
        return $this;
    }

    /**
     * @param array|null $updates
     * @return array|null
     */
    public static function parseUpdates(?array $updates): ?array
    {
        if (is_null($updates)) return null;
        return array_map(['self', 'parseUpdate'], $updates);
    }

}