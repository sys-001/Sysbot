<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PreCheckoutQuery
 * @package TelegramBot\Telegram\Types
 */
class PreCheckoutQuery
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
    public $currency;
    /**
     * @var
     */
    public $total_amount;
    /**
     * @var
     */
    public $invoice_payload;
    /**
     * @var
     */
    public $shipping_option_id;
    /**
     * @var
     */
    public $order_info;


    /**
     * @param null|\stdClass $pre_checkout_query
     * @return null|PreCheckoutQuery
     */
    public static function parsePreCheckoutQuery(?\stdClass $pre_checkout_query): ?self
    {
        if (is_null($pre_checkout_query)) {
            return null;
        }
        return (new self())
            ->setId($pre_checkout_query->id ?? null)
            ->setFrom(User::parseUser($pre_checkout_query->from ?? null))
            ->setCurrency($pre_checkout_query->currency ?? null)
            ->setTotalAmount($pre_checkout_query->total_amount ?? null)
            ->setInvoicePayload($pre_checkout_query->invoice_payload ?? null)
            ->setShippingOptionId($pre_checkout_query->shipping_option_id ?? null)
            ->setOrderInfo(OrderInfo::parseOrderInfo($pre_checkout_query->order_info ?? null));
    }

    /**
     * @param null|OrderInfo $order_info
     * @return PreCheckoutQuery
     */
    public function setOrderInfo(?OrderInfo $order_info): self
    {
        $this->order_info = $order_info;
        return $this;
    }

    /**
     * @param null|string $shipping_option_id
     * @return PreCheckoutQuery
     */
    public function setShippingOptionId(?string $shipping_option_id): self
    {
        $this->shipping_option_id = $shipping_option_id;
        return $this;
    }

    /**
     * @param null|string $invoice_payload
     * @return PreCheckoutQuery
     */
    public function setInvoicePayload(?string $invoice_payload): self
    {
        $this->invoice_payload = $invoice_payload;
        return $this;
    }

    /**
     * @param int|null $total_amount
     * @return PreCheckoutQuery
     */
    public function setTotalAmount(?int $total_amount): self
    {
        $this->total_amount = $total_amount;
        return $this;
    }

    /**
     * @param null|string $currency
     * @return PreCheckoutQuery
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param null|User $from
     * @return PreCheckoutQuery
     */
    public function setFrom(?User $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param null|string $id
     * @return PreCheckoutQuery
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

}