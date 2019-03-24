<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Location
 * @package TelegramBot\Telegram\Types
 */
class Location
{

    /**
     * @var
     */
    public $longitude;
    /**
     * @var
     */
    public $latitude;


    /**
     * @param null|\stdClass $location
     * @return null|Location
     */
    public static function parseLocation(?\stdClass $location): ?self
    {
        if (is_null($location)) {
            return null;
        }
        return (new self())
            ->setLongitude($location->longitude ?? null)
            ->setLatitude($location->latitude ?? null);
    }

    /**
     * @param float|null $latitude
     * @return Location
     */
    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @param float|null $longitude
     * @return Location
     */
    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

}