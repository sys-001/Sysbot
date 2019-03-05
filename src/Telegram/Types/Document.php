<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Document
 * @package TelegramBot\Telegram\Types
 */
class Document
{

    /**
     * @var
     */
    public $file_id;
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
     * @param null|\stdClass $document
     * @return null|Document
     */
    public static function parseDocument(?\stdClass $document): ?self
    {
        if (is_null($document)) return null;
        return (new self())
            ->setFileId($document->file_id ?? null)
            ->setThumb(PhotoSize::parsePhotoSize($document->thumb ?? null))
            ->setFileName($document->file_name ?? null)
            ->setMimeType($document->mime_type ?? null)
            ->setFileSize($document->file_size ?? null);
    }

    /**
     * @param int|null $file_size
     * @return Document
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|string $mime_type
     * @return Document
     */
    public function setMimeType(?string $mime_type): self
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @param null|string $file_name
     * @return Document
     */
    public function setFileName(?string $file_name): self
    {
        $this->file_name = $file_name;
        return $this;
    }

    /**
     * @param null|PhotoSize $thumb
     * @return Document
     */
    public function setThumb(?PhotoSize $thumb): self
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return Document
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }
}