<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ShippingAddress
 * @package TelegramBot\Telegram\Types
 */
class ShippingAddress
{

    /**
     * @var
     */
    public $country_code;
    /**
     * @var
     */
    public $state;
    /**
     * @var
     */
    public $city;
    /**
     * @var
     */
    public $street_line1;
    /**
     * @var
     */
    public $street_line2;
    /**
     * @var
     */
    public $post_code;


    /**
     * @param null|\stdClass $shipping_address
     * @return null|ShippingAddress
     */
    public static function parseShippingAddress(?\stdClass $shipping_address): ?self
    {
        if (is_null($shipping_address)) return null;
        return (new self())
            ->setCountryCode($shipping_address->country_code ?? null)
            ->setState($shipping_address->state ?? null)
            ->setCity($shipping_address->city ?? null)
            ->setStreetLine1($shipping_address->street_line1 ?? null)
            ->setStreetLine2($shipping_address->street_line2 ?? null)
            ->setPostCode($shipping_address->post_code ?? null);
    }

    /**
     * @param null|string $post_code
     * @return ShippingAddress
     */
    public function setPostCode(?string $post_code): self
    {
        $this->post_code = $post_code;
        return $this;
    }

    /**
     * @param null|string $street_line2
     * @return ShippingAddress
     */
    public function setStreetLine2(?string $street_line2): self
    {
        $this->street_line2 = $street_line2;
        return $this;
    }

    /**
     * @param null|string $street_line1
     * @return ShippingAddress
     */
    public function setStreetLine1(?string $street_line1): self
    {
        $this->street_line1 = $street_line1;
        return $this;
    }

    /**
     * @param null|string $city
     * @return ShippingAddress
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param null|string $state
     * @return ShippingAddress
     */
    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param null|string $country_code
     * @return ShippingAddress
     */
    public function setCountryCode(?string $country_code): self
    {
        $this->country_code = $country_code;
        return $this;
    }

}