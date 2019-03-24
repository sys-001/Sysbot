<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementErrorFiles
 * @package TelegramBot\Telegram\Types
 */
class PassportElementErrorFiles extends PassportElementError
{

    /**
     * @var
     */
    public $file_hashes;

    /**
     * @param null|\stdClass $passport_element_error_files
     * @return null|PassportElementErrorFiles
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error_files
    ): ?PassportElementErrorInterface {
        if (is_null($passport_element_error_files)) {
            return null;
        }
        return (new self())
            ->setFileHashes($passport_element_error_files->file_hashes ?? null)
            ->setSource($passport_element_error_files->source ?? null)
            ->setType($passport_element_error_files->type ?? null)
            ->setMessage($passport_element_error_files->message ?? null);
    }

    /**
     * @param array|null $file_hashes
     * @return PassportElementErrorFiles
     */
    public function setFileHashes(?array $file_hashes): PassportElementErrorInterface
    {
        $this->file_hashes = $file_hashes;
        return $this;
    }

}