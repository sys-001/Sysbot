<?php

namespace TelegramBot\Settings\Sections;

use TelegramBot\Exception\SettingsProviderException;

/**
 * Class TelegramSection
 * @package TelegramBot\Settings\Sections
 */
class TelegramSection implements SectionInterface
{
    /**
     * @var string
     */
    private $parse_mode;
    /**
     * @var bool
     */
    private $send_actions;
    /**
     * @var bool
     */
    private $use_test_api;

    /**
     * TelegramSection constructor.
     * @param string $parse_mode
     * @param bool $send_actions
     * @param bool $use_test_api
     * @throws SettingsProviderException
     */
    public function __construct(string $parse_mode, bool $send_actions, bool $use_test_api)
    {
        $available_modes = ["Markdown", "HTML"];
        if (!in_array($parse_mode, $available_modes)) {
            throw new SettingsProviderException("Invalid parse mode provided.");
        }
        $this->parse_mode = $parse_mode;
        $this->send_actions = $send_actions;
        $this->use_test_api = $use_test_api;
    }

    /**
     * @return string
     */
    public function getParseMode(): string
    {
        return $this->parse_mode;
    }

    /**
     * @param string $parse_mode
     * @return TelegramSection
     */
    public function setParseMode(string $parse_mode): TelegramSection
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSendActions(): bool
    {
        return $this->send_actions;
    }

    /**
     * @param bool $send_actions
     * @return TelegramSection
     */
    public function setSendActions(bool $send_actions): TelegramSection
    {
        $this->send_actions = $send_actions;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUseTestApi(): bool
    {
        return $this->use_test_api;
    }

    /**
     * @param bool $use_test_api
     * @return TelegramSection
     */
    public function setUseTestApi(bool $use_test_api): TelegramSection
    {
        $this->use_test_api = $use_test_api;
        return $this;
    }

}