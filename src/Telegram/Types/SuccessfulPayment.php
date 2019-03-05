<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class SuccessfulPayment
 * @package TelegramBot\Telegram\Types
 */
class SuccessfulPayment
{

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
     * @var
     */
    public $telegram_payment_charge_id;
    /**
     * @var
     */
    public $provider_payment_charge_id;


    /**
     * @param null|\stdClass $successful_payment
     * @return null|SuccessfulPayment
     */
    public static function parseSuccessfulPayment(?\stdClass $successful_payment): ?self
    {
        if (is_null($successful_payment)) return null;
        return (new self())
            ->setCurrency($successful_payment->currency ?? null)
            ->setTotalAmount($successful_payment->total_amount ?? null)
            ->setInvoicePayload($successful_payment->invoice_payload ?? null)
            ->setShippingOptionId($successful_payment->shipping_option_id ?? null)
            ->setOrderInfo(OrderInfo::parseOrderInfo($successful_payment->order_info ?? null))
            ->setTelegramPaymentChargeId($successful_payment->telegram_payment_charge_id ?? null)
            ->setProviderPaymentChargeId($successful_payment->provider_payment_charge_id ?? null);
    }

    /**
     * @param null|string $provider_payment_charge_id
     * @return SuccessfulPayment
     */
    public function setProviderPaymentChargeId(?string $provider_payment_charge_id): self
    {
        $this->provider_payment_charge_id = $provider_payment_charge_id;
        return $this;
    }

    /**
     * @param null|string $telegram_payment_charge_id
     * @return SuccessfulPayment
     */
    public function setTelegramPaymentChargeId(?string $telegram_payment_charge_id): self
    {
        $this->telegram_payment_charge_id = $telegram_payment_charge_id;
        return $this;
    }

    /**
     * @param null|OrderInfo $order_info
     * @return SuccessfulPayment
     */
    public function setOrderInfo(?OrderInfo $order_info): self
    {
        $this->order_info = $order_info;
        return $this;
    }

    /**
     * @param null|string $shipping_option_id
     * @return SuccessfulPayment
     */
    public function setShippingOptionId(?string $shipping_option_id): self
    {
        $this->shipping_option_id = $shipping_option_id;
        return $this;
    }

    /**
     * @param null|string $invoice_payload
     * @return SuccessfulPayment
     */
    public function setInvoicePayload(?string $invoice_payload): self
    {
        $this->invoice_payload = $invoice_payload;
        return $this;
    }

    /**
     * @param int|null $total_amount
     * @return SuccessfulPayment
     */
    public function setTotalAmount(?int $total_amount): self
    {
        $this->total_amount = $total_amount;
        return $this;
    }

    /**
     * @param null|string $currency
     * @return SuccessfulPayment
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

}