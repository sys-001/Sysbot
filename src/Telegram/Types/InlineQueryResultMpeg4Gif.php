<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultMpeg4Gif
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultMpeg4Gif extends InlineQueryResult
{

    /**
     * @var
     */
    public $mpeg4_url;
    /**
     * @var
     */
    public $mpeg4_width;
    /**
     * @var
     */
    public $mpeg4_height;
    /**
     * @var
     */
    public $mpeg4_duration;
    /**
     * @var
     */
    public $thumb_url;
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
     * @param null|\stdClass $inline_query_result_mpeg_4_gif
     * @return null|InlineQueryResultMpeg4Gif
     */
    public static function parseInlineQueryResultMpeg4Gif(?\stdClass $inline_query_result_mpeg_4_gif): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_mpeg_4_gif)) return null;
        return (new self())
            ->setMpeg4Url($inline_query_result_mpeg_4_gif->mpeg4_url ?? null)
            ->setMpeg4Width($inline_query_result_mpeg_4_gif->mpeg4_width ?? null)
            ->setMpeg4Height($inline_query_result_mpeg_4_gif->mpeg4_height ?? null)
            ->setMpeg4Duration($inline_query_result_mpeg_4_gif->mpeg4_duration ?? null)
            ->setThumbUrl($inline_query_result_mpeg_4_gif->thumb_url ?? null)
            ->setTitle($inline_query_result_mpeg_4_gif->title ?? null)
            ->setCaption($inline_query_result_mpeg_4_gif->caption ?? null)
            ->setParseMode($inline_query_result_mpeg_4_gif->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_mpeg_4_gif->input_message_content ?? null))
            ->setType($inline_query_result_mpeg_4_gif->type ?? null)
            ->setId($inline_query_result_mpeg_4_gif->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_mpeg_4_gif->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultMpeg4Gif
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultMpeg4Gif
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultMpeg4Gif
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultMpeg4Gif
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultMpeg4Gif
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param int|null $mpeg4_duration
     * @return InlineQueryResultMpeg4Gif
     */
    public function setMpeg4Duration(?int $mpeg4_duration): InlineQueryResultInterface
    {
        $this->mpeg4_duration = $mpeg4_duration;
        return $this;
    }

    /**
     * @param int|null $mpeg4_height
     * @return InlineQueryResultMpeg4Gif
     */
    public function setMpeg4Height(?int $mpeg4_height): InlineQueryResultInterface
    {
        $this->mpeg4_height = $mpeg4_height;
        return $this;
    }

    /**
     * @param int|null $mpeg4_width
     * @return InlineQueryResultMpeg4Gif
     */
    public function setMpeg4Width(?int $mpeg4_width): InlineQueryResultInterface
    {
        $this->mpeg4_width = $mpeg4_width;
        return $this;
    }

    /**
     * @param null|string $mpeg4_url
     * @return InlineQueryResultMpeg4Gif
     */
    public function setMpeg4Url(?string $mpeg4_url): InlineQueryResultInterface
    {
        $this->mpeg4_url = $mpeg4_url;
        return $this;
    }

}