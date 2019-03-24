<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultVenue
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultVenue extends InlineQueryResult
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
    public $address;
    /**
     * @var
     */
    public $foursquare_id;
    /**
     * @var
     */
    public $foursquare_type;
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
     * @param null|\stdClass $inline_query_result_venue
     * @return null|InlineQueryResultVenue
     */
    public static function parseInlineQueryResultVenue(?\stdClass $inline_query_result_venue
    ): ?InlineQueryResultInterface {
        if (is_null($inline_query_result_venue)) {
            return null;
        }
        return (new self())
            ->setLatitude($inline_query_result_venue->latitude ?? null)
            ->setLongitude($inline_query_result_venue->longitude ?? null)
            ->setTitle($inline_query_result_venue->title ?? null)
            ->setAddress($inline_query_result_venue->address ?? null)
            ->setFoursquareId($inline_query_result_venue->foursquare_id ?? null)
            ->setFoursquareType($inline_query_result_venue->foursquare_type ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_venue->input_message_content ?? null))
            ->setThumbUrl($inline_query_result_venue->thumb_url ?? null)
            ->setThumbWidth($inline_query_result_venue->thumb_width ?? null)
            ->setThumbHeight($inline_query_result_venue->thumb_height ?? null)
            ->setType($inline_query_result_venue->type ?? null)
            ->setId($inline_query_result_venue->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_venue->reply_markup ?? null));
    }

    /**
     * @param int|null $thumb_height
     * @return InlineQueryResultVenue
     */
    public function setThumbHeight(?int $thumb_height): InlineQueryResultInterface
    {
        $this->thumb_height = $thumb_height;
        return $this;
    }

    /**
     * @param int|null $thumb_width
     * @return InlineQueryResultVenue
     */
    public function setThumbWidth(?int $thumb_width): InlineQueryResultInterface
    {
        $this->thumb_width = $thumb_width;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultVenue
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultVenue
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $foursquare_type
     * @return InlineQueryResultVenue
     */
    public function setFoursquareType(?string $foursquare_type): InlineQueryResultInterface
    {
        $this->foursquare_type = $foursquare_type;
        return $this;
    }

    /**
     * @param null|string $foursquare_id
     * @return InlineQueryResultVenue
     */
    public function setFoursquareId(?string $foursquare_id): InlineQueryResultInterface
    {
        $this->foursquare_id = $foursquare_id;
        return $this;
    }

    /**
     * @param null|string $address
     * @return InlineQueryResultVenue
     */
    public function setAddress(?string $address): InlineQueryResultInterface
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultVenue
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param float|null $longitude
     * @return InlineQueryResultVenue
     */
    public function setLongitude(?float $longitude): InlineQueryResultInterface
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @param float|null $latitude
     * @return InlineQueryResultVenue
     */
    public function setLatitude(?float $latitude): InlineQueryResultInterface
    {
        $this->latitude = $latitude;
        return $this;
    }

}