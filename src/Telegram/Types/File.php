<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class File
 * @package TelegramBot\Telegram\Types
 */
class File
{

    /**
     * @var
     */
    public $file_id;
    /**
     * @var
     */
    public $file_size;
    /**
     * @var
     */
    public $file_path;


    /**
     * @param null|\stdClass $file
     * @return null|File
     */
    public static function parseFile(?\stdClass $file): ?self
    {
        if (is_null($file)) return null;
        return (new self())
            ->setFileId($file->file_id ?? null)
            ->setFileSize($file->file_size ?? null)
            ->setFilePath($file->file_path ?? null);
    }

    /**
     * @param null|string $file_path
     * @return File
     */
    public function setFilePath(?string $file_path): self
    {
        $this->file_path = $file_path;
        return $this;
    }

    /**
     * @param int|null $file_size
     * @return File
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return File
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }

}