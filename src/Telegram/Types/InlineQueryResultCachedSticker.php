<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedSticker
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedSticker extends InlineQueryResult
{

    /**
     * @var
     */
    public $sticker_file_id;
    /**
     * @var
     */
    public $input_message_content;

    /**
     * @param null|\stdClass $inline_query_result_cached_sticker
     * @return null|InlineQueryResultCachedSticker
     */
    public static function parseInlineQueryResultCachedSticker(?\stdClass $inline_query_result_cached_sticker): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_cached_sticker)) return null;
        return (new self())
            ->setStickerFileId($inline_query_result_cached_sticker->sticker_file_id ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_sticker->input_message_content ?? null))
            ->setType($inline_query_result_cached_sticker->type ?? null)
            ->setId($inline_query_result_cached_sticker->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_sticker->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedSticker
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $sticker_file_id
     * @return InlineQueryResultCachedSticker
     */
    public function setStickerFileId(?string $sticker_file_id): InlineQueryResultInterface
    {
        $this->sticker_file_id = $sticker_file_id;
        return $this;
    }

}