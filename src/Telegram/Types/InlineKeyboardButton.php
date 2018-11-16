<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineKeyboardButton
 * @package TelegramBot\Telegram\Types
 */
class InlineKeyboardButton
{

    /**
     * @var
     */
    public $text;
    /**
     * @var
     */
    public $url;
    /**
     * @var
     */
    public $callback_data;
    /**
     * @var
     */
    public $switch_inline_query;
    /**
     * @var
     */
    public $switch_inline_query_current_chat;
    /**
     * @var
     */
    public $callback_game;
    /**
     * @var
     */
    public $pay;


    /**
     * @param null|\stdClass $inline_keyboard_button
     * @return null|InlineKeyboardButton
     */
    public static function parseInlineKeyboardButton(?\stdClass $inline_keyboard_button): ?self
    {
        if (is_null($inline_keyboard_button)) return null;
        return (new self())
            ->setText($inline_keyboard_button->text ?? null)
            ->setUrl($inline_keyboard_button->url ?? '')
            ->setCallbackData($inline_keyboard_button->callback_data ?? null)
            ->setSwitchInlineQuery($inline_keyboard_button->switch_inline_query ?? null)
            ->setSwitchInlineQueryCurrentChat($inline_keyboard_button->switch_inline_query_current_chat ?? null)
            ->setCallbackGame(CallbackGame::parseCallbackGame($inline_keyboard_button->callback_game ?? null))
            ->setPay($inline_keyboard_button->pay ?? false);
    }

    /**
     * @param bool|null $pay
     * @return InlineKeyboardButton
     */
    public function setPay(?bool $pay): self
    {
        $this->pay = $pay;
        return $this;
    }

    /**
     * @param null|CallbackGame $callback_game
     * @return InlineKeyboardButton
     */
    public function setCallbackGame(?CallbackGame $callback_game): self
    {
        $this->callback_game = $callback_game;
        return $this;
    }

    /**
     * @param null|string $switch_inline_query_current_chat
     * @return InlineKeyboardButton
     */
    public function setSwitchInlineQueryCurrentChat(?string $switch_inline_query_current_chat): self
    {
        $this->switch_inline_query_current_chat = $switch_inline_query_current_chat;
        return $this;
    }

    /**
     * @param null|string $switch_inline_query
     * @return InlineKeyboardButton
     */
    public function setSwitchInlineQuery(?string $switch_inline_query): self
    {
        $this->switch_inline_query = $switch_inline_query;
        return $this;
    }

    /**
     * @param null|string $callback_data
     * @return InlineKeyboardButton
     */
    public function setCallbackData(?string $callback_data): self
    {
        $this->callback_data = $callback_data;
        return $this;
    }

    /**
     * @param null|string $url
     * @return InlineKeyboardButton
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param null|string $text
     * @return InlineKeyboardButton
     */
    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param array|null $inline_keyboard_buttons
     * @return array|null
     */
    public static function parseInlineKeyboardButtons(?array $inline_keyboard_buttons): ?array
    {
        if (is_null($inline_keyboard_buttons)) return null;
        return array_map(['self', 'parseInlineKeyboardButton'], $inline_keyboard_buttons);
    }
}