<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedGif
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedGif extends InlineQueryResult
{

    /**
     * @var
     */
    public $gif_file_id;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $caption;
    /**
     * @var
     */
    public $parse_mode;
    /**
     * @var
     */
    public $input_message_content;


    /**
     * @param null|\stdClass $inline_query_result_cached_gif
     * @return null|InlineQueryResultCachedGif
     */
    public static function parseInlineQueryResultCachedGif(?\stdClass $inline_query_result_cached_gif): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_cached_gif)) return null;
        return (new self())
            ->setGifFileId($inline_query_result_cached_gif->gif_file_id ?? null)
            ->setTitle($inline_query_result_cached_gif->title ?? null)
            ->setCaption($inline_query_result_cached_gif->caption ?? null)
            ->setParseMode($inline_query_result_cached_gif->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_gif->input_message_content ?? null))
            ->setType($inline_query_result_cached_gif->type ?? null)
            ->setId($inline_query_result_cached_gif->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_gif->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedGif
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultCachedGif
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultCachedGif
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultCachedGif
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $gif_file_id
     * @return InlineQueryResultCachedGif
     */
    public function setGifFileId(?string $gif_file_id): InlineQueryResultInterface
    {
        $this->gif_file_id = $gif_file_id;
        return $this;
    }

}