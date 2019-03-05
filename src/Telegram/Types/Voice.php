<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Voice
 * @package TelegramBot\Telegram\Types
 */
class Voice
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
    public $mime_type;
    /**
     * @var
     */
    public $file_size;


    /**
     * @param null|\stdClass $voice
     * @return null|Voice
     */
    public static function parseVoice(?\stdClass $voice): ?self
    {
        if (is_null($voice)) return null;
        return (new self())
            ->setFileId($voice->file_id ?? null)
            ->setDuration($voice->duration ?? null)
            ->setMimeType($voice->mime_type ?? null)
            ->setFileSize($voice->file_size ?? null);
    }

    /**
     * @param int|null $file_size
     * @return Voice
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|string $mime_type
     * @return Voice
     */
    public function setMimeType(?string $mime_type): self
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @param int|null $duration
     * @return Voice
     */
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return Voice
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }

}