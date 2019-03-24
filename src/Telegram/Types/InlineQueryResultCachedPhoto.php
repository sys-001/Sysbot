<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedPhoto
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedPhoto extends InlineQueryResult
{

    /**
     * @var
     */
    public $photo_file_id;
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
     * @param null|\stdClass $inline_query_result_cached_photo
     * @return null|InlineQueryResultCachedPhoto
     */
    public static function parseInlineQueryResultCachedPhoto(?\stdClass $inline_query_result_cached_photo
    ): ?InlineQueryResultInterface {
        if (is_null($inline_query_result_cached_photo)) {
            return null;
        }
        return (new self())
            ->setPhotoFileId($inline_query_result_cached_photo->photo_file_id ?? null)
            ->setTitle($inline_query_result_cached_photo->title ?? null)
            ->setDescription($inline_query_result_cached_photo->description ?? null)
            ->setCaption($inline_query_result_cached_photo->caption ?? null)
            ->setParseMode($inline_query_result_cached_photo->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_photo->input_message_content ?? null))
            ->setType($inline_query_result_cached_photo->type ?? null)
            ->setId($inline_query_result_cached_photo->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_photo->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedPhoto
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultCachedPhoto
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultCachedPhoto
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $description
     * @return InlineQueryResultCachedPhoto
     */
    public function setDescription(?string $description): InlineQueryResultInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultCachedPhoto
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $photo_file_id
     * @return InlineQueryResultCachedPhoto
     */
    public function setPhotoFileId(?string $photo_file_id): InlineQueryResultInterface
    {
        $this->photo_file_id = $photo_file_id;
        return $this;
    }

}