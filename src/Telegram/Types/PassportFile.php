<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportFile
 * @package TelegramBot\Telegram\Types
 */
class PassportFile
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
    public $file_date;


    /**
     * @param null|\stdClass $passport_file
     * @return null|PassportFile
     */
    public static function parsePassportFile(?\stdClass $passport_file): ?self
    {
        if (is_null($passport_file)) return null;
        return (new self())
            ->setFileId($passport_file->file_id ?? null)
            ->setFileSize($passport_file->file_size ?? null)
            ->setFileDate($passport_file->file_date ?? null);
    }

    /**
     * @param int|null $file_date
     * @return PassportFile
     */
    public function setFileDate(?int $file_date): self
    {
        $this->file_date = $file_date;
        return $this;
    }

    /**
     * @param int|null $file_size
     * @return PassportFile
     */
    public function setFileSize(?int $file_size): self
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @param null|string $file_id
     * @return PassportFile
     */
    public function setFileId(?string $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }

    /**
     * @param array|null $passport_files
     * @return array|null
     */
    public static function parsePassportFiles(?array $passport_files): ?array
    {
        if (is_null($passport_files)) return null;
        return array_map(['self', 'parsePassportFile'], $passport_files);
    }
}