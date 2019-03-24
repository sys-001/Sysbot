<?php

namespace TelegramBot;

use TelegramBot\{Exception\SettingsProviderException,
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
    private $path = "config/settings.json";
    /**
     * @var null
     */
    private $settings = null;

    /**
     * @var null|Logger
     */
    private $logger = null;

    /**
     * SettingsProvider constructor.
     * @param Logger $logger
     * @param string|null $path
     */
    function __construct(Logger $logger, string $path = null)
    {
        if (!empty($path)) {
            $this->path = $path;
        }
        $this->logger = $logger;
        if ($this->logger->getVerbosity() >= 1) {
            $log_message = sprintf("SettingsProvider: New SettingsProvider instance created. (Settings path: '%s')",
                $this->path);
            $this->logger->log($log_message);
        }
    }

    /**
     * @param string $path
     * @return SettingsProvider
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        if ($this->logger->getVerbosity() >= 1) {
            $log_message = sprintf("SettingsProvider: Settings path changed to '%s'.", $path);
            $this->logger->log($log_message);
        }
        return $this;
    }

    /**
     * @param AdminHandler|null $admin_handler
     * @param bool $check_ip
     * @param string $default_locale
     * @param string $default_locale_path
     * @param string $parse_mode
     * @param bool $send_actions
     * @param bool $use_test_api
     * @param bool $maintenance_enabled
     * @param string $maintenance_message
     * @param bool $antiflood_enabled
     * @param int $antiflood_seconds
     * @param int $antiflood_messages_number
     * @param int $antiflood_ban_seconds
     * @param string $antiflood_ban_message
     * @return SettingsProvider
     * @throws SettingsProviderException
     */
    function createSettings(
        AdminHandler $admin_handler = null,
        bool $check_ip = true,
        string $default_locale = "en",
        string $default_locale_path = "languages/en.json",
        string $parse_mode = "HTML",
        bool $send_actions = true,
        bool $use_test_api = false,
        bool $maintenance_enabled = false,
        string $maintenance_message = "Bot in maintenance, please wait.",
        bool $antiflood_enabled = true,
        int $antiflood_seconds = 5,
        int $antiflood_messages_number = 5,
        int $antiflood_ban_seconds = 120,
        string $antiflood_ban_message = "Flood detected, you're banned for 2 minutes."
    ): self {
        if (null === $admin_handler) {
            $admin_handler = new AdminHandler([]);
        }
        $general = new Sections\GeneralSection($admin_handler, $check_ip, $default_locale, $default_locale_path);
        $telegram = new Sections\TelegramSection($parse_mode, $send_actions, $use_test_api);
        $maintenance = new Sections\MaintenanceSection($maintenance_enabled, $maintenance_message);
        $antiflood = new Sections\AntifloodSection($antiflood_enabled, $antiflood_seconds, $antiflood_messages_number,
            $antiflood_ban_seconds, $antiflood_ban_message);
        $this->settings = new Settings($general, $telegram, $maintenance, $antiflood);
        if ($this->logger->getVerbosity() >= 1) {
            $log_message = sprintf("SettingsProvider: New Settings instance created. (%s)", json_encode([
                'general' => [
                    'admins' => $admin_handler->getAdmins(),
                    'check_ip' => $check_ip,
                    'default_locale' => $default_locale,
                    'default_locale_path' => $default_locale_path
                ],
                'telegram' => [
                    'parse_mode' => $parse_mode,
                    'send_actions' => $send_actions,
                    'use_test_api' => $use_test_api,
                ],
                'maintenance' => [
                    'enabled' => $maintenance_enabled,
                    'message' => $maintenance_message
                ],
                'antiflood' => [
                    'enabled' => $antiflood_enabled,
                    'messages_seconds' => $antiflood_seconds,
                    'messages_number' => $antiflood_messages_number,
                    'ban_seconds' => $antiflood_ban_seconds,
                    'ban_message' => $antiflood_ban_message
                ]
            ], JSON_UNESCAPED_SLASHES));
            $this->logger->log($log_message);
        }
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
        $settings_json = json_encode([
            'general' => [
                'admins' => $general->getAdminHandler()->getAdmins(),
                'check_ip' => $general->getCheckIp(),
                'default_locale' => $general->getDefaultLocale(),
                'default_locale_path' => $general->getDefaultLocalePath()
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
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $result = file_put_contents($this->path, $settings_json);
        if (false === $result) {
            throw new SettingsProviderException("Could not save settings");
        }
        if ($this->logger->getVerbosity() >= 1) {
            $log_message = sprintf("SettingsProvider: Settings saved to path '%s'. (%s)", $this->path, $settings_json);
            $this->logger->log($log_message);
        }
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
        if (false === $settings_file or empty($settings_file)) {
            throw new SettingsProviderException("Could not load settings");
        }
        $settings = json_decode($settings_file);
        if (null === $settings) {
            throw new SettingsProviderException("Invalid settings file");
        }
        $admin_handler = new AdminHandler($settings->general->admins);
        $general_section = new Sections\GeneralSection($admin_handler, $settings->general->check_ip,
            $settings->general->default_locale, $settings->general->default_locale_path);
        $telegram_section = new Sections\TelegramSection($settings->telegram->parse_mode,
            $settings->telegram->send_actions, $settings->telegram->use_test_api);
        $maintenance_section = new Sections\MaintenanceSection($settings->maintenance->enabled,
            $settings->maintenance->message);
        $antiflood_section = new Sections\AntifloodSection($settings->antiflood->enabled,
            $settings->antiflood->messages_seconds, $settings->antiflood->messages_number,
            $settings->antiflood->ban_seconds, $settings->antiflood->ban_message);
        $this->settings = new Settings($general_section, $telegram_section, $maintenance_section, $antiflood_section);
        if ($this->logger->getVerbosity() >= 1) {
            $log_message = sprintf("SettingsProvider: Settings loaded from file at path '%s'. (%s)", $this->path,
                $settings_file);
            $this->logger->log($log_message);
        }
        return $this;
    }
}