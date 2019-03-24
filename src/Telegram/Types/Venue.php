<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Venue
 * @package TelegramBot\Telegram\Types
 */
class Venue
{

    /**
     * @var
     */
    public $location;
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
     * @param null|\stdClass $venue
     * @return null|Venue
     */
    public static function parseVenue(?\stdClass $venue): ?self
    {
        if (is_null($venue)) {
            return null;
        }
        return (new self())
            ->setLocation(Location::parseLocation($venue->location ?? null))
            ->setTitle($venue->title ?? null)
            ->setAddress($venue->address ?? null)
            ->setFoursquareId($venue->foursquare_id ?? null)
            ->setFoursquareType($venue->foursquare_type ?? null);
    }

    /**
     * @param null|string $foursquare_type
     * @return Venue
     */
    public function setFoursquareType(?string $foursquare_type): self
    {
        $this->foursquare_type = $foursquare_type;
        return $this;
    }

    /**
     * @param null|string $foursquare_id
     * @return Venue
     */
    public function setFoursquareId(?string $foursquare_id): self
    {
        $this->foursquare_id = $foursquare_id;
        return $this;
    }

    /**
     * @param null|string $address
     * @return Venue
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param null|string $title
     * @return Venue
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|Location $location
     * @return Venue
     */
    public function setLocation(?Location $location): self
    {
        $this->location = $location;
        return $this;
    }

}