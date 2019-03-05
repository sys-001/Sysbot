<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementErrorDataField
 * @package TelegramBot\Telegram\Types
 */
class PassportElementErrorDataField extends PassportElementError
{

    /**
     * @var
     */
    public $field_name;
    /**
     * @var
     */
    public $data_hash;


    /**
     * @param null|\stdClass $passport_element_error_data_field
     * @return null|PassportElementErrorDataField
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error_data_field): ?PassportElementErrorInterface
    {
        if (is_null($passport_element_error_data_field)) return null;
        return (new self())
            ->setFieldName($passport_element_error_data_field->field_name ?? null)
            ->setDataHash($passport_element_error_data_field->data_hash ?? null)
            ->setSource($passport_element_error_data_field->source ?? null)
            ->setType($passport_element_error_data_field->type ?? null)
            ->setMessage($passport_element_error_data_field->message ?? null);
    }

    /**
     * @param null|string $data_hash
     * @return PassportElementErrorDataField
     */
    public function setDataHash(?string $data_hash): PassportElementErrorInterface
    {
        $this->data_hash = $data_hash;
        return $this;
    }

    /**
     * @param null|string $field_name
     * @return PassportElementErrorDataField
     */
    public function setFieldName(?string $field_name): PassportElementErrorInterface
    {
        $this->field_name = $field_name;
        return $this;
    }

}