<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class MaskPosition
 * @package TelegramBot\Telegram\Types
 */
class MaskPosition
{

    /**
     * @var
     */
    public $point;
    /**
     * @var
     */
    public $x_shift;
    /**
     * @var
     */
    public $y_shift;
    /**
     * @var
     */
    public $scale;


    /**
     * @param null|\stdClass $mask_position
     * @return null|MaskPosition
     */
    public static function parseMaskPosition(?\stdClass $mask_position): ?self
    {
        if (is_null($mask_position)) return null;
        return (new self())
            ->setPoint($mask_position->point ?? null)
            ->setXShift($mask_position->x_shift ?? null)
            ->setYShift($mask_position->y_shift ?? null)
            ->setScale($mask_position->scale ?? null);
    }

    /**
     * @param float|null $scale
     * @return MaskPosition
     */
    public function setScale(?float $scale): self
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * @param float|null $y_shift
     * @return MaskPosition
     */
    public function setYShift(?float $y_shift): self
    {
        $this->y_shift = $y_shift;
        return $this;
    }

    /**
     * @param float|null $x_shift
     * @return MaskPosition
     */
    public function setXShift(?float $x_shift): self
    {
        $this->x_shift = $x_shift;
        return $this;
    }

    /**
     * @param null|string $point
     * @return MaskPosition
     */
    public function setPoint(?string $point): self
    {
        $this->point = $point;
        return $this;
    }

}