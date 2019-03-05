<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementErrorTranslationFiles
 * @package TelegramBot\Telegram\Types
 */
class PassportElementErrorTranslationFiles extends PassportElementError
{

    /**
     * @var
     */
    public $file_hashes;


    /**
     * @param null|\stdClass $passport_element_error_translation_files
     * @return null|PassportElementErrorTranslationFiles
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error_translation_files): ?PassportElementErrorInterface
    {
        if (is_null($passport_element_error_translation_files)) return null;
        return (new self())
            ->setFileHashes($passport_element_error_translation_files->file_hashes ?? null)
            ->setSource($passport_element_error_translation_files->source ?? null)
            ->setType($passport_element_error_translation_files->type ?? null)
            ->setMessage($passport_element_error_translation_files->message ?? null);
    }

    /**
     * @param array|null $file_hashes
     * @return PassportElementErrorTranslationFiles
     */
    public function setFileHashes(?array $file_hashes): PassportElementErrorInterface
    {
        $this->file_hashes = $file_hashes;
        return $this;
    }

}