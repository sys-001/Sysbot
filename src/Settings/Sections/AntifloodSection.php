<?php

namespace TelegramBot\Settings\Sections;

/**
 * Class AntifloodSection
 * @package TelegramBot\Settings\Sections
 */
class AntifloodSection implements SectionInterface
{
    /**
     * @var bool
     */
    private $enabled;
    /**
     * @var int
     */
    private $messages_seconds;
    /**
     * @var int
     */
    private $messages_number;
    /**
     * @var int
     */
    private $ban_seconds;
    /**
     * @var string
     */
    private $ban_message;

    /**
     * AntifloodSection constructor.
     * @param bool $enabled
     * @param int $messages_seconds
     * @param int $messages_number
     * @param int $ban_seconds
     * @param string $ban_message
     */
    public function __construct(
        bool $enabled,
        int $messages_seconds,
        int $messages_number,
        int $ban_seconds,
        string $ban_message
    )
    {
        $this->enabled = $enabled;
        $this->messages_seconds = $messages_seconds;
        $this->messages_number = $messages_number;
        $this->ban_seconds = $ban_seconds;
        $this->ban_message = $ban_message;
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
     * @return AntifloodSection
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return int
     */
    public function getMessagesSeconds(): int
    {
        return $this->messages_seconds;
    }

    /**
     * @param int $messages_seconds
     * @return AntifloodSection
     */
    public function setMessagesSeconds(int $messages_seconds): self
    {
        $this->messages_seconds = $messages_seconds;
        return $this;
    }

    /**
     * @return int
     */
    public function getMessagesNumber(): int
    {
        return $this->messages_number;
    }

    /**
     * @param int $messages_number
     * @return AntifloodSection
     */
    public function setMessagesNumber(int $messages_number): self
    {
        $this->messages_number = $messages_number;
        return $this;
    }

    /**
     * @return int
     */
    public function getBanSeconds(): int
    {
        return $this->ban_seconds;
    }

    /**
     * @param int $ban_seconds
     * @return AntifloodSection
     */
    public function setBanSeconds(int $ban_seconds): self
    {
        $this->ban_seconds = $ban_seconds;
        return $this;
    }

    /**
     * @return string
     */
    public function getBanMessage(): string
    {
        return $this->ban_message;
    }

    /**
     * @param string $ban_message
     * @return AntifloodSection
     */
    public function setBanMessage(string $ban_message): self
    {
        $this->ban_message = $ban_message;
        return $this;
    }


}