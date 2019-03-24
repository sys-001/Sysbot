<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Invoice
 * @package TelegramBot\Telegram\Types
 */
class Invoice
{

    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $description;
    /**
     * @var
     */
    public $start_parameter;
    /**
     * @var
     */
    public $currency;
    /**
     * @var
     */
    public $total_amount;


    /**
     * @param null|\stdClass $invoice
     * @return null|Invoice
     */
    public static function parseInvoice(?\stdClass $invoice): ?self
    {
        if (is_null($invoice)) {
            return null;
        }
        return (new self())
            ->setTitle($invoice->title ?? null)
            ->setDescription($invoice->description ?? null)
            ->setStartParameter($invoice->start_parameter ?? null)
            ->setCurrency($invoice->currency ?? null)
            ->setTotalAmount($invoice->total_amount ?? null);
    }

    /**
     * @param int|null $total_amount
     * @return Invoice
     */
    public function setTotalAmount(?int $total_amount): self
    {
        $this->total_amount = $total_amount;
        return $this;
    }

    /**
     * @param null|string $currency
     * @return Invoice
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param null|string $start_parameter
     * @return Invoice
     */
    public function setStartParameter(?string $start_parameter): self
    {
        $this->start_parameter = $start_parameter;
        return $this;
    }

    /**
     * @param null|string $description
     * @return Invoice
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param null|string $title
     * @return Invoice
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

}