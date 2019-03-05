<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class CallbackQuery
 * @package TelegramBot\Telegram\Types
 */
class CallbackQuery
{

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $from;
    /**
     * @var
     */
    public $message;
    /**
     * @var
     */
    public $inline_message_id;
    /**
     * @var
     */
    public $chat_instance;
    /**
     * @var
     */
    public $data;
    /**
     * @var
     */
    public $game_short_name;


    /**
     * @param null|\stdClass $callback_query
     * @return null|CallbackQuery
     */
    public static function parseCallbackQuery(?\stdClass $callback_query): ?self
    {
        if (is_null($callback_query)) return null;
        return (new self())
            ->setId($callback_query->id ?? null)
            ->setFrom(User::parseUser($callback_query->from ?? null))
            ->setMessage(Message::parseMessage($callback_query->message ?? null))
            ->setInlineMessageId($callback_query->inline_message_id ?? null)
            ->setChatInstance($callback_query->chat_instance ?? null)
            ->setData($callback_query->data ?? null)
            ->setGameShortName($callback_query->game_short_name ?? null);
    }

    /**
     * @param null|string $game_short_name
     * @return CallbackQuery
     */
    public function setGameShortName(?string $game_short_name): self
    {
        $this->game_short_name = $game_short_name;
        return $this;
    }

    /**
     * @param null|string $data
     * @return CallbackQuery
     */
    public function setData(?string $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param null|string $chat_instance
     * @return CallbackQuery
     */
    public function setChatInstance(?string $chat_instance): self
    {
        $this->chat_instance = $chat_instance;
        return $this;
    }

    /**
     * @param null|string $inline_message_id
     * @return CallbackQuery
     */
    public function setInlineMessageId(?string $inline_message_id): self
    {
        $this->inline_message_id = $inline_message_id;
        return $this;
    }

    /**
     * @param null|Message $message
     * @return CallbackQuery
     */
    public function setMessage(?Message $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param null|User $from
     * @return CallbackQuery
     */
    public function setFrom(?User $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param null|string $id
     * @return CallbackQuery
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

}