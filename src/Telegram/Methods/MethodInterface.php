<?php

namespace TelegramBot\Telegram\Methods;


/**
 * Interface MethodInterface
 * @package TelegramBot\Telegram\Methods
 */
interface MethodInterface
{
    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * @return string
     */
    public function getMethodName(): string;

    /**
     * @return bool
     */
    public function isMultipart(): bool;

    /**
     * @return array
     */
    public function getResultParams(): array;
}