<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Audio
 * @package TelegramBot\Telegram\Types
 */
class Audio
{

    /**
     * @var
     */
    public $file_id;
    /**
     * @var
     */
    public $duration;
    /**
     * @var
     */
    public $performer;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $mime_type;
    /**
     * @var
     */
    public $file_size;
    /**
     * @var
     */
    public $thumb;

    /**
     * @param null|\stdClass $audio
     * @return null|Audio
     */
    public static function parseAudio(?\stdClass $audio): ?self
    {
        if (is_null($audio)) return null;
        return (new self())
            ->setFileId($audio->file_id ?? null)
            ->setDuration($audio->duration ?? null)
            ->setPerformer($audio->performer ?? null)
            ->setTitle($audio->title ?? null)
            ->setMimeType($audio->mime_type ?? null)
            ->setFileSize($audio->file_size ?? null)
            ->setThumb(PhotoSize::parsePhotoSize($audio->thumb ?? null));
    }

    /**
     * @param null|PhotoSize $thumb
     * @return Audio
     */
    public function setThumb(?PhotoSize $thumb): self
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @param int|null $file_size
     * @return Audio
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|string $mime_type
     * @return Audio
     */
    public function setMimeType(?string $mime_type): self
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @param null|string $title
     * @return Audio
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $performer
     * @return Audio
     */
    public function setPerformer(?string $performer): self
    {
        $this->performer = $performer;
        return $this;
    }

    /**
     * @param int|null $duration
     * @return Audio
     */
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return Audio
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }
}