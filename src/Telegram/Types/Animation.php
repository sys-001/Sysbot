<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Animation
 * @package TelegramBot\Telegram\Types
 */
class Animation
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
    public $duration;
    /**
     * @var
     */
    public $thumb;
    /**
     * @var
     */
    public $file_name;
    /**
     * @var
     */
    public $mime_type;
    /**
     * @var
     */
    public $file_size;


    /**
     * @param null|\stdClass $animation
     * @return null|Animation
     */
    public static function parseAnimation(?\stdClass $animation): ?self
    {
        if (is_null($animation)) return null;
        return (new self())
            ->setFileId($animation->file_id ?? null)
            ->setWidth($animation->width ?? null)
            ->setHeight($animation->height ?? null)
            ->setDuration($animation->duration ?? null)
            ->setThumb(PhotoSize::parsePhotoSize($animation->thumb ?? null))
            ->setFileName($animation->file_name ?? null)
            ->setMimeType($animation->mime_type ?? null)
            ->setFileSize($animation->file_size ?? null);
    }

    /**
     * @param int|null $file_size
     * @return Animation
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|string $mime_type
     * @return Animation
     */
    public function setMimeType(?string $mime_type): self
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @param null|string $file_name
     * @return Animation
     */
    public function setFileName(?string $file_name): self
    {
        $this->file_name = $file_name;
        return $this;
    }

    /**
     * @param null|PhotoSize $thumb
     * @return Animation
     */
    public function setThumb(?PhotoSize $thumb): self
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @param int|null $duration
     * @return Animation
     */
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param int|null $height
     * @return Animation
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int|null $width
     * @return Animation
     */
    public function setWidth(?int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return Animation
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }

}