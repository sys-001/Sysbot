<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedMpeg4Gif
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedMpeg4Gif extends InlineQueryResult
{

    /**
     * @var
     */
    public $mpeg4_file_id;
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
     * @param null|\stdClass $inline_query_result_cached_mpeg_4_gif
     * @return null|InlineQueryResultCachedMpeg4Gif
     */
    public static function parseInlineQueryResultCachedMpeg4Gif(?\stdClass $inline_query_result_cached_mpeg_4_gif): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_cached_mpeg_4_gif)) return null;
        return (new self())
            ->setMpeg4FileId($inline_query_result_cached_mpeg_4_gif->mpeg4_file_id ?? null)
            ->setTitle($inline_query_result_cached_mpeg_4_gif->title ?? null)
            ->setCaption($inline_query_result_cached_mpeg_4_gif->caption ?? null)
            ->setParseMode($inline_query_result_cached_mpeg_4_gif->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_mpeg_4_gif->input_message_content ?? null))
            ->setType($inline_query_result_cached_mpeg_4_gif->type ?? null)
            ->setId($inline_query_result_cached_mpeg_4_gif->id ?? null)
            ->setReplyMarkup(InlinekeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_mpeg_4_gif->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedMpeg4Gif
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultCachedMpeg4Gif
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultCachedMpeg4Gif
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultCachedMpeg4Gif
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $mpeg4_file_id
     * @return InlineQueryResultCachedMpeg4Gif
     */
    public function setMpeg4FileId(?string $mpeg4_file_id): InlineQueryResultInterface
    {
        $this->mpeg4_file_id = $mpeg4_file_id;
        return $this;
    }

}