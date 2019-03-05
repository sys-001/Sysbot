<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultGame
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultGame extends InlineQueryResult
{

    /**
     * @var
     */
    public $game_short_name;


    /**
     * @param null|\stdClass $inline_query_result_game
     * @return null|InlineQueryResultGame
     */
    public static function parseInlineQueryResultGame(?\stdClass $inline_query_result_game): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_game)) return null;
        return (new self())
            ->setGameShortName($inline_query_result_game->game_short_name ?? null)
            ->setType($inline_query_result_game->type ?? null)
            ->setId($inline_query_result_game->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_game->reply_markup ?? null));
    }

    /**
     * @param null|string $game_short_name
     * @return InlineQueryResultGame
     */
    public function setGameShortName(?string $game_short_name): InlineQueryResultInterface
    {
        $this->game_short_name = $game_short_name;
        return $this;
    }

}