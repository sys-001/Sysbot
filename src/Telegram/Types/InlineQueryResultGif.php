<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultGif
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultGif extends InlineQueryResult
{

    /**
     * @var
     */
    public $gif_url;
    /**
     * @var
     */
    public $gif_width;
    /**
     * @var
     */
    public $gif_height;
    /**
     * @var
     */
    public $gif_duration;
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
     * @param null|\stdClass $inline_query_result_gif
     * @return null|InlineQueryResultGif
     */
    public static function parseInlineQueryResultGif(?\stdClass $inline_query_result_gif): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_gif)) return null;
        return (new self())
            ->setGifUrl($inline_query_result_gif->gif_url ?? null)
            ->setGifWidth($inline_query_result_gif->gif_width ?? null)
            ->setGifHeight($inline_query_result_gif->gif_height ?? null)
            ->setGifDuration($inline_query_result_gif->gif_duration ?? null)
            ->setThumbUrl($inline_query_result_gif->thumb_url ?? null)
            ->setTitle($inline_query_result_gif->title ?? null)
            ->setCaption($inline_query_result_gif->caption ?? null)
            ->setParseMode($inline_query_result_gif->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_gif->input_message_content ?? null))
            ->setType($inline_query_result_gif->type ?? null)
            ->setId($inline_query_result_gif->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_gif->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultGif
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultGif
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultGif
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultGif
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultGif
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param int|null $gif_duration
     * @return InlineQueryResultGif
     */
    public function setGifDuration(?int $gif_duration): InlineQueryResultInterface
    {
        $this->gif_duration = $gif_duration;
        return $this;
    }

    /**
     * @param int|null $gif_height
     * @return InlineQueryResultGif
     */
    public function setGifHeight(?int $gif_height): InlineQueryResultInterface
    {
        $this->gif_height = $gif_height;
        return $this;
    }

    /**
     * @param int|null $gif_width
     * @return InlineQueryResultGif
     */
    public function setGifWidth(?int $gif_width): InlineQueryResultInterface
    {
        $this->gif_width = $gif_width;
        return $this;
    }

    /**
     * @param null|string $gif_url
     * @return InlineQueryResultGif
     */
    public function setGifUrl(?string $gif_url): InlineQueryResultInterface
    {
        $this->gif_url = $gif_url;
        return $this;
    }

}