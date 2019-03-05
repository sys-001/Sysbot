<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class OrderInfo
 * @package TelegramBot\Telegram\Types
 */
class OrderInfo
{

    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $phone_number;
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $shipping_address;


    /**
     * @param null|\stdClass $order_info
     * @return null|OrderInfo
     */
    public static function parseOrderInfo(?\stdClass $order_info): ?self
    {
        if (is_null($order_info)) return null;
        return (new self())
            ->setName($order_info->name ?? null)
            ->setPhoneNumber($order_info->phone_number ?? null)
            ->setEmail($order_info->email ?? null)
            ->setShippingAddress(ShippingAddress::parseShippingAddress($order_info->shipping_address ?? null));
    }

    /**
     * @param null|ShippingAddress $shipping_address
     * @return OrderInfo
     */
    public function setShippingAddress(?ShippingAddress $shipping_address): self
    {
        $this->shipping_address = $shipping_address;
        return $this;
    }

    /**
     * @param null|string $email
     * @return OrderInfo
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param null|string $phone_number
     * @return OrderInfo
     */
    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;
        return $this;
    }

    /**
     * @param null|string $name
     * @return OrderInfo
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

}