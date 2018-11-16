<?php

namespace TelegramBot\Telegram\Types;


/**
 * Interface PassportElementErrorInterface
 * @package TelegramBot\Telegram\Types
 */
interface PassportElementErrorInterface
{
    /**
     * @param null|\stdClass $passport_element_error
     * @return null|PassportElementErrorInterface
     */
    public static function parsePassportElementError(?\stdClass $passport_element_error): ?self;

    /**
     * @param null|string $source
     * @return PassportElementErrorInterface
     */
    public function setSource(?string $source): self;

    /**
     * @param null|string $type
     * @return PassportElementErrorInterface
     */
    public function setType(?string $type): self;

    /**
     * @param null|string $message
     * @return PassportElementErrorInterface
     */
    public function setMessage(?string $message): self;
}