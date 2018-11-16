<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultLocation
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultLocation extends InlineQueryResult
{

    /**
     * @var
     */
    public $latitude;
    /**
     * @var
     */
    public $longitude;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $live_period;
    /**
     * @var
     */
    public $input_message_content;
    /**
     * @var
     */
    public $thumb_url;
    /**
     * @var
     */
    public $thumb_width;
    /**
     * @var
     */
    public $thumb_height;


    /**
     * @param null|\stdClass $inline_query_result_location
     * @return null|InlineQueryResultLocation
     */
    public static function parseInlineQueryResultLocation(?\stdClass $inline_query_result_location): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_location)) return null;
        return (new self())
            ->setLatitude($inline_query_result_location->latitude ?? null)
            ->setLongitude($inline_query_result_location->longitude ?? null)
            ->setTitle($inline_query_result_location->title ?? null)
            ->setLivePeriod($inline_query_result_location->live_period ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_location->input_message_content ?? null))
            ->setThumbUrl($inline_query_result_location->thumb_url ?? null)
            ->setThumbWidth($inline_query_result_location->thumb_width ?? null)
            ->setThumbHeight($inline_query_result_location->thumb_height ?? null)
            ->setType($inline_query_result_location->type ?? null)
            ->setId($inline_query_result_location->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_location->reply_markup ?? null));
    }

    /**
     * @param int|null $thumb_height
     * @return InlineQueryResultLocation
     */
    public function setThumbHeight(?int $thumb_height): InlineQueryResultInterface
    {
        $this->thumb_height = $thumb_height;
        return $this;
    }

    /**
     * @param int|null $thumb_width
     * @return InlineQueryResultLocation
     */
    public function setThumbWidth(?int $thumb_width): InlineQueryResultInterface
    {
        $this->thumb_width = $thumb_width;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultLocation
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultLocation
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param int|null $live_period
     * @return InlineQueryResultLocation
     */
    public function setLivePeriod(?int $live_period): InlineQueryResultInterface
    {
        $this->live_period = $live_period;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultLocation
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param float|null $longitude
     * @return InlineQueryResultLocation
     */
    public function setLongitude(?float $longitude): InlineQueryResultInterface
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @param float|null $latitude
     * @return InlineQueryResultLocation
     */
    public function setLatitude(?float $latitude): InlineQueryResultInterface
    {
        $this->latitude = $latitude;
        return $this;
    }

}