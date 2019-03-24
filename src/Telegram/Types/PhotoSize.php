<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PhotoSize
 * @package TelegramBot\Telegram\Types
 */
class PhotoSize
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
    public $file_size;


    /**
     * @param null|\stdClass $photo_size
     * @return null|PhotoSize
     */
    public static function parsePhotoSize(?\stdClass $photo_size): ?self
    {
        if (is_null($photo_size)) {
            return null;
        }
        return (new self())
            ->setFileId($photo_size->file_id ?? null)
            ->setWidth($photo_size->width ?? null)
            ->setHeight($photo_size->height ?? null)
            ->setFileSize($photo_size->file_size ?? null);
    }

    /**
     * @param int|null $file_size
     * @return PhotoSize
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param int|null $height
     * @return PhotoSize
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int|null $width
     * @return PhotoSize
     */
    public function setWidth(?int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return PhotoSize
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }

    /**
     * @param array|null $photo_sizes
     * @return array|null
     */
    public static function parsePhotoSizes(?array $photo_sizes): ?array
    {
        if (is_null($photo_sizes)) {
            return null;
        }
        return array_map(['self', 'parsePhotoSize'], $photo_sizes);
    }

}