<?php


namespace TelegramBot\Event;


use TelegramBot\Exception\EventException;

/**
 * Class Trigger
 * @package TelegramBot\Event
 */
class Trigger
{
    /**
     * @var string
     */
    private $regex;

    /**
     * Trigger constructor.
     * @param string $command
     * @param bool $strict_starting
     * @param bool $strict_ending
     * @throws EventException
     */
    function __construct(string $command, bool $strict_starting = true, bool $strict_ending = true)
    {
        $command = preg_quote($command, '/');
        if ($strict_starting) {
            $command = sprintf('^%s', $command);
        }
        if ($strict_ending) {
            $command = sprintf('%s$', $command);
        }
        $regex = sprintf('/%s/', $command);
        if (false === @preg_match($regex, null)) {
            throw new EventException('Invalid command provided');
        }
        $this->regex = $regex;
    }

    /**
     * @param string $regex
     * @return Trigger
     * @throws EventException
     */
    public static function createByRegex(string $regex): self
    {
        return (new self(''))
            ->setRegex($regex);
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @param string $regex
     * @return Trigger
     * @throws EventException
     */
    public function setRegex(string $regex): self
    {
        if (false === @preg_match($regex, null)) {
            throw new EventException('Invalid regex provided');
        }
        $this->regex = $regex;
        return $this;
    }
}