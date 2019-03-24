<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementErrorSelfie
 * @package TelegramBot\Telegram\Types
 */
class PassportElementErrorSelfie extends PassportElementError
{

    /**
     * @var
     */
    public $file_hash;


    /**
     * @param null|\stdClass $passport_element_error_selfie
     * @return null|PassportElementErrorSelfie
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error_selfie
    ): ?PassportElementErrorInterface {
        if (is_null($passport_element_error_selfie)) {
            return null;
        }
        return (new self())
            ->setFileHash($passport_element_error_selfie->file_hash ?? null)
            ->setSource($passport_element_error_selfie->source ?? null)
            ->setType($passport_element_error_selfie->type ?? null)
            ->setMessage($passport_element_error_selfie->message ?? null);
    }

    /**
     * @param null|string $file_hash
     * @return PassportElementErrorSelfie
     */
    public function setFileHash(?string $file_hash): PassportElementErrorInterface
    {
        $this->file_hash = $file_hash;
        return $this;
    }

}