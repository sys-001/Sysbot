<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementErrorReverseSide
 * @package TelegramBot\Telegram\Types
 */
class PassportElementErrorReverseSide extends PassportElementError
{

    /**
     * @var
     */
    public $file_hash;


    /**
     * @param null|\stdClass $passport_element_error_reverse_side
     * @return null|PassportElementErrorReverseSide
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error_reverse_side): ?PassportElementErrorInterface
    {
        if (is_null($passport_element_error_reverse_side)) return null;
        return (new self())
            ->setFileHash($passport_element_error_reverse_side->file_hash ?? null)
            ->setSource($passport_element_error_reverse_side->source ?? null)
            ->setType($passport_element_error_reverse_side->type ?? null)
            ->setMessage($passport_element_error_reverse_side->message ?? null);
    }

    /**
     * @param null|string $file_hash
     * @return PassportElementErrorReverseSide
     */
    public function setFileHash(?string $file_hash): PassportElementErrorInterface
    {
        $this->file_hash = $file_hash;
        return $this;
    }

}