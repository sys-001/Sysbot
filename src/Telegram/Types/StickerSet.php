<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class StickerSet
 * @package TelegramBot\Telegram\Types
 */
class StickerSet
{

    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $contains_masks;
    /**
     * @var
     */
    public $stickers;


    /**
     * @param null|\stdClass $sticker_set
     * @return null|StickerSet
     */
    public static function parseStickerSet(?\stdClass $sticker_set): ?self
    {
        if (is_null($sticker_set)) return null;
        return (new self())
            ->setName($sticker_set->name ?? null)
            ->setTitle($sticker_set->title ?? null)
            ->setContainsMasks($sticker_set->contains_masks ?? null)
            ->setStickers(Sticker::parseStickers($sticker_set->stickers ?? null));
    }

    /**
     * @param array|null $stickers
     * @return StickerSet
     */
    public function setStickers(?array $stickers): self
    {
        $this->stickers = $stickers;
        return $this;
    }

    /**
     * @param bool|null $contains_masks
     * @return StickerSet
     */
    public function setContainsMasks(?bool $contains_masks): self
    {
        $this->contains_masks = $contains_masks;
        return $this;
    }

    /**
     * @param null|string $title
     * @return StickerSet
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $name
     * @return StickerSet
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

}