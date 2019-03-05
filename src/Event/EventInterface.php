<?php


namespace TelegramBot\Event;


/**
 * Interface EventInterface
 * @package TelegramBot\Event
 */
interface EventInterface
{
    /**
     * @return \Closure
     */
    public function getCallback(): \Closure;

    /**
     * @return Trigger
     */
    public function getTrigger(): Trigger;

    /**
     * @return string
     */
    public function getUuid(): string;
}