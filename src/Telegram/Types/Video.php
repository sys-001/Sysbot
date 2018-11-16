<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Video
 * @package TelegramBot\Telegram\Types
 */
class Video
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
    public $mime_type;
    /**
     * @var
     */
    public $file_size;


    /**
     * @param null|\stdClass $video
     * @return null|Video
     */
    public static function parseVideo(?\stdClass $video): ?self
    {
        if (is_null($video)) return null;
        return (new self())
            ->setFileId($video->file_id ?? null)
            ->setWidth($video->width ?? null)
            ->setHeight($video->height ?? null)
            ->setDuration($video->duration ?? null)
            ->setThumb(PhotoSize::parsePhotoSize($video->thumb ?? null))
            ->setMimeType($video->mime_type ?? null)
            ->setFileSize($video->file_size ?? null);
    }

    /**
     * @param int|null $file_size
     * @return Video
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|string $mime_type
     * @return Video
     */
    public function setMimeType(?string $mime_type): self
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @param null|PhotoSize $thumb
     * @return Video
     */
    public function setThumb(?PhotoSize $thumb): self
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @param int|null $duration
     * @return Video
     */
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param int|null $height
     * @return Video
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int|null $width
     * @return Video
     */
    public function setWidth(?int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return Video
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }
}