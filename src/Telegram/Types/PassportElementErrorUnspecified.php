<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementErrorUnspecified
 * @package TelegramBot\Telegram\Types
 */
class PassportElementErrorUnspecified extends PassportElementError
{

    /**
     * @var
     */
    public $element_hash;


    /**
     * @param null|\stdClass $passport_element_error_unspecified
     * @return null|PassportElementErrorUnspecified
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error_unspecified
    ): ?PassportElementErrorInterface {
        if (is_null($passport_element_error_unspecified)) {
            return null;
        }
        return (new self())
            ->setElementHash($passport_element_error_unspecified->element_hash ?? null)
            ->setSource($passport_element_error_unspecified->source ?? null)
            ->setType($passport_element_error_unspecified->type ?? null)
            ->setMessage($passport_element_error_unspecified->message ?? null);
    }

    /**
     * @param null|string $element_hash
     * @return PassportElementErrorUnspecified
     */
    public function setElementHash(?string $element_hash): PassportElementErrorInterface
    {
        $this->element_hash = $element_hash;
        return $this;
    }

}