<?php


namespace TelegramBot\Event;


/**
 * Class DefaultEvent
 * @package TelegramBot\Event
 */
abstract class DefaultEvent implements EventInterface
{

    /**
     * @var string
     */
    public static $type;
    /**
     * @var string
     */
    public static $update_path;
    /**
     * @var bool
     */
    public $admins_only;
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
     * @param bool $admins_only
     */
    function __construct(Trigger $trigger, \Closure $callback, bool $admins_only = false)
    {
        $this->uuid = uniqid();
        $this->trigger = $trigger;
        $this->callback = $callback;
        $this->admins_only = $admins_only;
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