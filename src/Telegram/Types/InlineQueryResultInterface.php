<?php

namespace TelegramBot\Telegram\Types;


/**
 * Interface InlineQueryResultInterface
 * @package TelegramBot\Telegram\Types
 */
interface InlineQueryResultInterface
{
    /**
     * @param null|\stdClass $inline_query_result
     * @return null|InlineQueryResultInterface
     */
    public static function parseInlineQueryResult(?\stdClass $inline_query_result): ?self;

    /**
     * @param null|string $type
     * @return InlineQueryResultInterface
     */
    public function setType(?string $type): self;

    /**
     * @param null|string $id
     * @return InlineQueryResultInterface
     */
    public function setId(?string $id): self;

    /**
     * @param null|InlineKeyboardMarkup $reply_markup
     * @return InlineQueryResultInterface
     */
    public function setReplyMarkup(?InlineKeyboardMarkup $reply_markup): self;
}