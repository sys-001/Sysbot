<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class LabeledPrice
 * @package TelegramBot\Telegram\Types
 */
class LabeledPrice
{

    /**
     * @var
     */
    public $label;
    /**
     * @var
     */
    public $amount;


    /**
     * @param null|\stdClass $labeled_price
     * @return null|LabeledPrice
     */
    public static function parseLabeledPrice(?\stdClass $labeled_price): ?self
    {
        if (is_null($labeled_price)) {
            return null;
        }
        return (new self())
            ->setLabel($labeled_price->label ?? null)
            ->setAmount($labeled_price->amount ?? null);
    }

    /**
     * @param int|null $amount
     * @return LabeledPrice
     */
    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param null|string $label
     * @return LabeledPrice
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param array|null $labeled_prices
     * @return array|null
     */
    public static function parseLabeledPrices(?array $labeled_prices): ?array
    {
        if (is_null($labeled_prices)) {
            return null;
        }
        return array_map(['self', 'parseLabeledPrice'], $labeled_prices);
    }

}