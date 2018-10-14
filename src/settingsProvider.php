<?php

namespace TelegramBot;

use TelegramBot\Types\{Exception\SettingsProviderException,
    Settings\Handlers\AdminHandler,
    Settings\Sections,
    Settings\Settings};

/**
 * Class SettingsProvider
 * @package TelegramBot
 */
class SettingsProvider
{
    /**
     * @var string
     */
    private $path = "data/management/bot.settings";
    /**
     * @var null
     */
    private $settings = null;

    /**
     * SettingsProvider constructor.
     * @param string|null $path
     */
    function __construct(string $path = null)
    {
        if (!empty($path)) $this->path = $path;
    }

    /**
     * @param string $path
     * @return SettingsProvider
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param AdminHandler|null $admins
     * @param string $administration_password
     * @param string $parse_mode
     * @param bool $send_actions
     * @param bool $use_test_api
     * @param bool $maintenance_enabled
     * @param string $maintenance_message
     * @param bool $antiflood_enabled
     * @param int $antiflood_seconds
     * @param int $antiflood_messages_number
     * @param int $antiflood_ban_minutes
     * @param string $antiflood_ban_message
     * @return SettingsProvider
     * @throws SettingsProviderException
     */
    function createSettings(AdminHandler $admins = null, string $administration_password = "password", string $parse_mode = "HTML", bool $send_actions = true, bool $use_test_api = false, bool $maintenance_enabled = false, string $maintenance_message = "Bot in maintenance, please wait.", bool $antiflood_enabled = true, int $antiflood_seconds = 5, int $antiflood_messages_number = 5, int $antiflood_ban_minutes = 2, string $antiflood_ban_message = "Flood detected, you're banned for 2 minutes."): self
    {
        if (null == $admins) $admins = new AdminHandler();
        $general = new Sections\GeneralSection($admins, $administration_password);
        $telegram = new Sections\TelegramSection($parse_mode, $send_actions, $use_test_api);
        $maintenance = new Sections\MaintenanceSection($maintenance_enabled, $maintenance_message);
        $antiflood = new Sections\AntiFloodSection($antiflood_enabled, $antiflood_seconds, $antiflood_messages_number, $antiflood_ban_minutes, $antiflood_ban_message);
        $this->settings = new Settings($general, $telegram, $maintenance, $antiflood);
        return $this;
    }

    /**
     * @throws SettingsProviderException
     */
    function saveSettings(): void
    {
        $settings = $this->getSettings();
        $general = $settings->getGeneralSection();
        $telegram = $settings->getTelegramSection();
        $maintenance = $settings->getMaintenanceSection();
        $antiflood = $settings->getAntifloodSection();
        $result = file_put_contents($this->path, json_encode([
            'general' => [
                'admins' => $general->getAdminHandler()->getAdmins(),
                'administration_password' => $general->getAdministrationPassword()
            ],
            'telegram' => [
                'parse_mode' => $telegram->getParseMode(),
                'send_actions' => $telegram->getSendActions(),
                'use_test_api' => $telegram->getUseTestApi(),
            ],
            'maintenance' => [
                'enabled' => $maintenance->isEnabled(),
                'message' => $maintenance->getMessage()
            ],
            'antiflood' => [
                'enabled' => $antiflood->isEnabled(),
                'messages_seconds' => $antiflood->getMessagesSeconds(),
                'messages_number' => $antiflood->getMessagesNumber(),
                'ban_seconds' => $antiflood->getBanSeconds(),
                'ban_message' => $antiflood->getBanMessage()
            ]
        ], JSON_PRETTY_PRINT));
        if (false == $result) throw new SettingsProviderException("Could not save settings.");
        return;
    }

    /**
     * @return Settings
     */
    function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @return SettingsProvider
     * @throws SettingsProviderException
     */
    function loadSettings(): self
    {
        $settings_file = file_get_contents($this->path);
        if (false == $settings_file or empty($settings_file)) throw new SettingsProviderException("Could not load settings.");
        $settings = json_decode($settings_file);
        if (null == $settings) throw new SettingsProviderException("Invalid settings file.");
        $admin_handler = new AdminHandler($settings->general->admins);
        $general_section = new Sections\GeneralSection($admin_handler, $settings->general->administration_password);
        $telegram_section = new Sections\TelegramSection($settings->telegram->parse_mode, $settings->telegram->send_actions, $settings->telegram->use_test_api);
        $maintenance_section = new Sections\MaintenanceSection($settings->maintenance->enabled, $settings->maintenance->message);
        $antiflood_section = new Sections\AntifloodSection($settings->antiflood->enabled, $settings->antiflood->messages_seconds, $settings->antiflood->messages_number, $settings->antiflood->ban_seconds, $settings->antiflood->ban_message);
        $this->settings = new Settings($general_section, $telegram_section, $maintenance_section, $antiflood_section);
        return $this;
    }
}