<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportElementError
 * @package TelegramBot\Telegram\Types
 */
class PassportElementError implements PassportElementErrorInterface
{


    /**
     * @var
     */
    public $source;

    /**
     * @var
     */
    public $type;

    /**
     * @var
     */
    public $message;


    /**
     * @param null|\stdClass $passport_element_error
     * @return null|PassportElementError
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error): ?PassportElementErrorInterface
    {
        if (empty($passport_element_error->type)) {
            return null;
        }
        $class_name = sprintf('PassportElementError%s', ucfirst($passport_element_error->type));
        return call_user_func([$class_name, 'parsePassportElementError'], $passport_element_error);
    }


    /**
     * @param null|string $source
     * @return PassportElementError
     */
    public function setSource(?string $source): PassportElementErrorInterface
    {
        $this->source = $source;
        return $this;
    }


    /**
     * @param null|string $type
     * @return PassportElementError
     */
    public function setType(?string $type): PassportElementErrorInterface
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @param null|string $message
     * @return PassportElementError
     */
    public function setMessage(?string $message): PassportElementErrorInterface
    {
        $this->message = $message;
        return $this;
    }

}