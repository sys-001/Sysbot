<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementErrorTranslationFile
 * @package TelegramBot\Telegram\Types
 */
class PassportElementErrorTranslationFile extends PassportElementError
{

    /**
     * @var
     */
    public $file_hash;


    /**
     * @param null|\stdClass $passport_element_error_translation_file
     * @return null|PassportElementErrorTranslationFile
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error_translation_file): ?PassportElementErrorInterface
    {
        if (is_null($passport_element_error_translation_file)) return null;
        return (new self())
            ->setFileHash($passport_element_error_translation_file->file_hash ?? null)
            ->setSource($passport_element_error_translation_file->source ?? null)
            ->setType($passport_element_error_translation_file->type ?? null)
            ->setMessage($passport_element_error_translation_file->message ?? null);
    }

    /**
     * @param null|string $file_hash
     * @return PassportElementErrorTranslationFile
     */
    public function setFileHash(?string $file_hash): PassportElementErrorInterface
    {
        $this->file_hash = $file_hash;
        return $this;
    }

}