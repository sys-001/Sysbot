<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedVideo
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedVideo extends InlineQueryResult
{

    /**
     * @var
     */
    public $video_file_id;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $description;
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
     * @param null|\stdClass $inline_query_result_cached_video
     * @return null|InlineQueryResultCachedVideo
     */
    public static function parseInlineQueryResultCachedVideo(?\stdClass $inline_query_result_cached_video): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_cached_video)) return null;
        return (new self())
            ->setVideoFileId($inline_query_result_cached_video->video_file_id ?? null)
            ->setTitle($inline_query_result_cached_video->title ?? null)
            ->setDescription($inline_query_result_cached_video->description ?? null)
            ->setCaption($inline_query_result_cached_video->caption ?? null)
            ->setParseMode($inline_query_result_cached_video->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_video->input_message_content ?? null))
            ->setType($inline_query_result_cached_video->type ?? null)
            ->setId($inline_query_result_cached_video->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_video->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedVideo
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): self
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultCachedVideo
     */
    public function setParseMode(?string $parse_mode): self
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultCachedVideo
     */
    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $description
     * @return InlineQueryResultCachedVideo
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultCachedVideo
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $video_file_id
     * @return InlineQueryResultCachedVideo
     */
    public function setVideoFileId(?string $video_file_id): self
    {
        $this->video_file_id = $video_file_id;
        return $this;
    }

}