<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputVenueMessageContent
 * @package TelegramBot\Telegram\Types
 */
class InputVenueMessageContent extends InputMessageContent
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
     * @param null|\stdClass $input_venue_message_content
     * @return null|InputVenueMessageContent
     */
    public static function parseInputMessageContent(?\stdClass $input_venue_message_content): ?InputMessageContentInterface
    {
        if (is_null($input_venue_message_content)) return null;
        return (new self())
            ->setLatitude($input_venue_message_content->latitude ?? null)
            ->setLongitude($input_venue_message_content->longitude ?? null)
            ->setTitle($input_venue_message_content->title ?? null)
            ->setAddress($input_venue_message_content->address ?? null)
            ->setFoursquareId($input_venue_message_content->foursquare_id ?? null)
            ->setFoursquareType($input_venue_message_content->foursquare_type ?? null);
    }

    /**
     * @param null|string $foursquare_type
     * @return InputVenueMessageContent
     */
    public function setFoursquareType(?string $foursquare_type): InputMessageContentInterface
    {
        $this->foursquare_type = $foursquare_type;
        return $this;
    }

    /**
     * @param null|string $foursquare_id
     * @return InputVenueMessageContent
     */
    public function setFoursquareId(?string $foursquare_id): InputMessageContentInterface
    {
        $this->foursquare_id = $foursquare_id;
        return $this;
    }

    /**
     * @param null|string $address
     * @return InputVenueMessageContent
     */
    public function setAddress(?string $address): InputMessageContentInterface
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InputVenueMessageContent
     */
    public function setTitle(?string $title): InputMessageContentInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param float|null $longitude
     * @return InputVenueMessageContent
     */
    public function setLongitude(?float $longitude): InputMessageContentInterface
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @param float|null $latitude
     * @return InputVenueMessageContent
     */
    public function setLatitude(?float $latitude): InputMessageContentInterface
    {
        $this->latitude = $latitude;
        return $this;
    }

}