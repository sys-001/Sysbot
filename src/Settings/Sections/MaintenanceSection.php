<?php

namespace TelegramBot\Settings\Sections;

/**
 * Class MaintenanceSection
 * @package TelegramBot\Settings\Sections
 */
class MaintenanceSection implements SectionInterface
{
    /**
     * @var bool
     */
    private $enabled;
    /**
     * @var string
     */
    private $message;

    /**
     * MaintenanceSection constructor.
     * @param bool $enabled
     * @param string $message
     */
    public function __construct(bool $enabled, string $message)
    {
        $this->enabled = $enabled;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return MaintenanceSection
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return MaintenanceSection
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }


}