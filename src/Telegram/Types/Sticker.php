<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Sticker
 * @package TelegramBot\Telegram\Types
 */
class Sticker
{

    /**
     * @var
     */
    public $file_id;
    /**
     * @var
     */
    public $width;
    /**
     * @var
     */
    public $height;
    /**
     * @var
     */
    public $thumb;
    /**
     * @var
     */
    public $emoji;
    /**
     * @var
     */
    public $set_name;
    /**
     * @var
     */
    public $mask_position;
    /**
     * @var
     */
    public $file_size;


    /**
     * @param null|\stdClass $sticker
     * @return null|Sticker
     */
    public static function parseSticker(?\stdClass $sticker): ?self
    {
        if (is_null($sticker)) return null;
        return (new self())
            ->setFileId($sticker->file_id ?? null)
            ->setWidth($sticker->width ?? null)
            ->setHeight($sticker->height ?? null)
            ->setThumb(PhotoSize::parsePhotoSize($sticker->thumb ?? null))
            ->setEmoji($sticker->emoji ?? null)
            ->setSetName($sticker->set_name ?? null)
            ->setMaskPosition(MaskPosition::parseMaskPosition($sticker->mask_position ?? null))
            ->setFileSize($sticker->file_size ?? null);
    }

    /**
     * @param int|null $file_size
     * @return Sticker
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|MaskPosition $mask_position
     * @return Sticker
     */
    public function setMaskPosition(?MaskPosition $mask_position): self
    {
        $this->mask_position = $mask_position;
        return $this;
    }

    /**
     * @param null|string $set_name
     * @return Sticker
     */
    public function setSetName(?string $set_name): self
    {
        $this->set_name = $set_name;
        return $this;
    }

    /**
     * @param null|string $emoji
     * @return Sticker
     */
    public function setEmoji(?string $emoji): self
    {
        $this->emoji = $emoji;
        return $this;
    }

    /**
     * @param null|PhotoSize $thumb
     * @return Sticker
     */
    public function setThumb(?PhotoSize $thumb): self
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @param int|null $height
     * @return Sticker
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int|null $width
     * @return Sticker
     */
    public function setWidth(?int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return Sticker
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }

    /**
     * @param array|null $stickers
     * @return array|null
     */
    public static function parseStickers(?array $stickers): ?array
    {
        if (is_null($stickers)) return null;
        return array_map(['self', 'parseSticker'], $stickers);
    }

}