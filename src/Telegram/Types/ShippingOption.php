<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ShippingOption
 * @package TelegramBot\Telegram\Types
 */
class ShippingOption
{

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $prices;


    /**
     * @param null|\stdClass $shipping_option
     * @return null|ShippingOption
     */
    public static function parseShippingOption(?\stdClass $shipping_option): ?self
    {
        if (is_null($shipping_option)) {
            return null;
        }
        return (new self())
            ->setId($shipping_option->id ?? null)
            ->setTitle($shipping_option->title ?? null)
            ->setPrices(LabeledPrice::parseLabeledPrices($shipping_option->prices ?? null));
    }

    /**
     * @param array|null $prices
     * @return ShippingOption
     */
    public function setPrices(?array $prices): self
    {
        $this->prices = $prices;
        return $this;
    }

    /**
     * @param null|string $title
     * @return ShippingOption
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $id
     * @return ShippingOption
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

}