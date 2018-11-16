<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultVideo
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultVideo extends InlineQueryResult
{

    /**
     * @var
     */
    public $video_url;
    /**
     * @var
     */
    public $mime_type;
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
    public $video_width;
    /**
     * @var
     */
    public $video_height;
    /**
     * @var
     */
    public $video_duration;
    /**
     * @var
     */
    public $description;
    /**
     * @var
     */
    public $input_message_content;


    /**
     * @param null|\stdClass $inline_query_result_video
     * @return null|InlineQueryResultVideo
     */
    public static function parseInlineQueryResultVideo(?\stdClass $inline_query_result_video): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_video)) return null;
        return (new self())
            ->setVideoUrl($inline_query_result_video->video_url ?? null)
            ->setMimeType($inline_query_result_video->mime_type ?? null)
            ->setThumbUrl($inline_query_result_video->thumb_url ?? null)
            ->setTitle($inline_query_result_video->title ?? null)
            ->setCaption($inline_query_result_video->caption ?? null)
            ->setParseMode($inline_query_result_video->parse_mode ?? null)
            ->setVideoWidth($inline_query_result_video->video_width ?? null)
            ->setVideoHeight($inline_query_result_video->video_height ?? null)
            ->setVideoDuration($inline_query_result_video->video_duration ?? null)
            ->setDescription($inline_query_result_video->description ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_video->input_message_content ?? null))
            ->setType($inline_query_result_video->type ?? null)
            ->setId($inline_query_result_video->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_video->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultVideo
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $description
     * @return InlineQueryResultVideo
     */
    public function setDescription(?string $description): InlineQueryResultInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param int|null $video_duration
     * @return InlineQueryResultVideo
     */
    public function setVideoDuration(?int $video_duration): InlineQueryResultInterface
    {
        $this->video_duration = $video_duration;
        return $this;
    }

    /**
     * @param int|null $video_height
     * @return InlineQueryResultVideo
     */
    public function setVideoHeight(?int $video_height): InlineQueryResultInterface
    {
        $this->video_height = $video_height;
        return $this;
    }

    /**
     * @param int|null $video_width
     * @return InlineQueryResultVideo
     */
    public function setVideoWidth(?int $video_width): InlineQueryResultInterface
    {
        $this->video_width = $video_width;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultVideo
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultVideo
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultVideo
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultVideo
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param null|string $mime_type
     * @return InlineQueryResultVideo
     */
    public function setMimeType(?string $mime_type): InlineQueryResultInterface
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @param null|string $video_url
     * @return InlineQueryResultVideo
     */
    public function setVideoUrl(?string $video_url): InlineQueryResultInterface
    {
        $this->video_url = $video_url;
        return $this;
    }

}