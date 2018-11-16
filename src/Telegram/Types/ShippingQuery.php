<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ShippingQuery
 * @package TelegramBot\Telegram\Types
 */
class ShippingQuery
{

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $from;
    /**
     * @var
     */
    public $invoice_payload;
    /**
     * @var
     */
    public $shipping_address;


    /**
     * @param null|\stdClass $shipping_query
     * @return null|ShippingQuery
     */
    public static function parseShippingQuery(?\stdClass $shipping_query): ?self
    {
        if (is_null($shipping_query)) return null;
        return (new self())
            ->setId($shipping_query->id ?? null)
            ->setFrom(User::parseUser($shipping_query->from ?? null))
            ->setInvoicePayload($shipping_query->invoice_payload ?? null)
            ->setShippingAddress(ShippingAddress::parseShippingAddress($shipping_query->shipping_address ?? null));
    }

    /**
     * @param null|ShippingAddress $shipping_address
     * @return ShippingQuery
     */
    public function setShippingAddress(?ShippingAddress $shipping_address): self
    {
        $this->shipping_address = $shipping_address;
        return $this;
    }

    /**
     * @param null|string $invoice_payload
     * @return ShippingQuery
     */
    public function setInvoicePayload(?string $invoice_payload): self
    {
        $this->invoice_payload = $invoice_payload;
        return $this;
    }

    /**
     * @param null|User $from
     * @return ShippingQuery
     */
    public function setFrom(?User $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param null|string $id
     * @return ShippingQuery
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

}