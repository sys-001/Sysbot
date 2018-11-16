<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class VideoNote
 * @package TelegramBot\Telegram\Types
 */
class VideoNote
{

    /**
     * @var
     */
    public $file_id;
    /**
     * @var
     */
    public $length;
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
    public $file_size;


    /**
     * @param null|\stdClass $video_note
     * @return null|VideoNote
     */
    public static function parseVideoNote(?\stdClass $video_note): ?self
    {
        if (is_null($video_note)) return null;
        return (new self())
            ->setFileId($video_note->file_id ?? null)
            ->setLength($video_note->length ?? null)
            ->setDuration($video_note->duration ?? null)
            ->setThumb(PhotoSize::parsePhotoSize($video_note->thumb ?? null))
            ->setFileSize($video_note->file_size ?? null);
    }

    /**
     * @param int|null $file_size
     * @return VideoNote
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|PhotoSize $thumb
     * @return VideoNote
     */
    public function setThumb(?PhotoSize $thumb): self
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @param int|null $duration
     * @return VideoNote
     */
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param int|null $length
     * @return VideoNote
     */
    public function setLength(?int $length): self
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return VideoNote
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }

}