<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultPhoto
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultPhoto extends InlineQueryResult
{

    /**
     * @var
     */
    public $photo_url;
    /**
     * @var
     */
    public $thumb_url;
    /**
     * @var
     */
    public $photo_width;
    /**
     * @var
     */
    public $photo_height;
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
     * @param null|\stdClass $inline_query_result_photo
     * @return null|InlineQueryResultPhoto
     */
    public static function parseInlineQueryResultPhoto(?\stdClass $inline_query_result_photo
    ): ?InlineQueryResultInterface {
        if (is_null($inline_query_result_photo)) {
            return null;
        }
        return (new self())
            ->setPhotoUrl($inline_query_result_photo->photo_url ?? null)
            ->setThumbUrl($inline_query_result_photo->thumb_url ?? null)
            ->setPhotoWidth($inline_query_result_photo->photo_width ?? null)
            ->setPhotoHeight($inline_query_result_photo->photo_height ?? null)
            ->setTitle($inline_query_result_photo->title ?? null)
            ->setDescription($inline_query_result_photo->description ?? null)
            ->setCaption($inline_query_result_photo->caption ?? null)
            ->setParseMode($inline_query_result_photo->parse_mode ?? null)
            ->setInputMessageContent($inline_query_result_photo->input_message_content ?? null)
            ->setType($inline_query_result_photo->type ?? null)
            ->setId($inline_query_result_photo->id ?? null)
            ->setReplyMarkup($inline_query_result_photo->reply_markup ?? null);
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultPhoto
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultPhoto
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultPhoto
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $description
     * @return InlineQueryResultPhoto
     */
    public function setDescription(?string $description): InlineQueryResultInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultPhoto
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param int|null $photo_height
     * @return InlineQueryResultPhoto
     */
    public function setPhotoHeight(?int $photo_height): InlineQueryResultInterface
    {
        $this->photo_height = $photo_height;
        return $this;
    }

    /**
     * @param int|null $photo_width
     * @return InlineQueryResultPhoto
     */
    public function setPhotoWidth(?int $photo_width): InlineQueryResultInterface
    {
        $this->photo_width = $photo_width;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultPhoto
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param null|string $photo_url
     * @return InlineQueryResultPhoto
     */
    public function setPhotoUrl(?string $photo_url): InlineQueryResultInterface
    {
        $this->photo_url = $photo_url;
        return $this;
    }

}