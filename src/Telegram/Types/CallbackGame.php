<?php

namespace TelegramBot\Telegram\Types;


/**
 * Class CallbackGame
 * @package TelegramBot\Telegram\Types
 */
class CallbackGame
{

    /**
     * @var
     */
    public $callback_game;

    /**
     * @param null|\stdClass $callback_game
     * @return null|CallbackGame
     */
    public static function parseCallbackGame(?\stdClass $callback_game): ?self
    {
        if (is_null($callback_game)) return null;
        return (new self())
            ->setCallbackGame($callback_game ?? null);
    }

    /**
     * @param null|\stdClass $callback_game
     * @return CallbackGame
     */
    public function setCallbackGame(?\stdClass $callback_game): self
    {
        $this->callback_game = $callback_game;
        return $this;
    }

}