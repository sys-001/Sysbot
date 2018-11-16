<?php


namespace TelegramBot\Event;


/**
 * Class DefaultEvent
 * @package TelegramBot\Event
 */
abstract class DefaultEvent implements EventInterface
{

    /**
     * @var
     */
    public static $type;
    /**
     * @var
     */
    public static $update_path;
    /**
     * @var \Closure
     */
    private $callback;
    /**
     * @var Trigger
     */
    private $trigger;
    /**
     * @var string
     */
    private $uuid;

    /**
     * DefaultEvent constructor.
     * @param Trigger $trigger
     * @param \Closure $callback
     */
    function __construct(Trigger $trigger, \Closure $callback)
    {
        $this->uuid = uniqid();
        $this->trigger = $trigger;
        $this->callback = $callback;
    }

    /**
     * @return \Closure
     */
    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    /**
     * @return Trigger
     */
    public function getTrigger(): Trigger
    {
        return $this->trigger;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

}