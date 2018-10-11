<?php

class SettingsProviderException extends Exception
{
}

class SettingsProvider
{

    private $settings_path = "data/management/bot.settings";

    /**
     * SettingsProvider constructor.
     * @param string|null $settings_path
     */
    function __construct(string $settings_path = null)
    {
        if (!empty($settings_path)) $this->settings_path = $settings_path;
    }

    /**
     * @param array $admins
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
     * @return stdClass
     * @throws SettingsProviderException
     */
    function createSettings(array $admins = [], string $administration_password = "password", string $parse_mode = "HTML", bool $send_actions = true, bool $use_test_api = false, bool $maintenance_enabled = false, string $maintenance_message = "Bot in maintenance, please wait.", bool $antiflood_enabled = true, int $antiflood_seconds = 5, int $antiflood_messages_number = 5, int $antiflood_ban_minutes = 2, string $antiflood_ban_message = "Flood detected, you're banned for 2 minutes."): stdClass
    {
        $general = $this->generateGeneralSection($admins, $administration_password);
        if (!$this->validateGeneralSection($general)) throw new settingsProviderException("Invalid arguments provided");
        $telegram = $this->generateTelegramSection($parse_mode, $send_actions, $use_test_api);
        if (!$this->validateTelegramSection($telegram)) throw new settingsProviderException("Invalid arguments provided");
        $maintenance = $this->generateMaintenanceSection($maintenance_enabled, $maintenance_message);
        if (!$this->validateMaintenanceSection($maintenance)) throw new settingsProviderException("Invalid arguments provided");
        $antiflood = $this->generateAntiFloodSection($antiflood_enabled, $antiflood_seconds, $antiflood_messages_number, $antiflood_ban_minutes, $antiflood_ban_message);
        if (!$this->validateAntiFloodSection($antiflood)) throw new settingsProviderException("Invalid arguments provided");
        $settings = ['general' => $general,
            'telegram' => $telegram,
            'maintenance' => $maintenance,
            'antiflood' => $antiflood
        ];
        return (object)$settings;
    }

    /**
     * @param array $admins
     * @param string $administration_password
     * @return stdClass
     */
    private function generateGeneralSection(array $admins, string $administration_password): stdClass
    {
        return (object)['admins' => $admins,
            'administration_password' => hash("sha512", $administration_password),
        ];

    }

    /**
     * @param stdClass $general_section
     * @return bool
     */
    private function validateGeneralSection(stdClass $general_section): bool
    {
        if (!is_array($general_section->admins) or !is_string($general_section->administration_password)) return false;
        return true;
    }

    /**
     * @param string $parse_mode
     * @param bool $send_actions
     * @param bool $use_test_api
     * @return stdClass
     */
    private function generateTelegramSection(string $parse_mode, bool $send_actions, bool $use_test_api): stdClass
    {
        return (object)['parse_mode' => $parse_mode,
            'send_actions' => $send_actions,
            'use_test_api' => $use_test_api
        ];
    }

    /**
     * @param stdClass $telegram_section
     * @return bool
     */
    private function validateTelegramSection(stdClass $telegram_section): bool
    {
        if (!in_array($telegram_section->parse_mode, ["HTML", "Markdown"]) or !is_bool($telegram_section->send_actions) or !is_bool($telegram_section->use_test_api)) return false;
        return true;
    }

    /**
     * @param bool $enabled
     * @param string $message
     * @return stdClass
     */
    private function generateMaintenanceSection(bool $enabled, string $message): stdClass
    {
        return (object)['enabled' => $enabled,
            'message' => $message
        ];

    }

    /**
     * @param stdClass $maintenance_section
     * @return bool
     */
    private function validateMaintenanceSection(stdClass $maintenance_section): bool
    {
        if (!is_bool($maintenance_section->enabled) or !is_string($maintenance_section->message)) return false;
        return true;
    }

    /**
     * @param bool $enabled
     * @param int $seconds
     * @param int $messages_number
     * @param int $ban_minutes
     * @param string $ban_message
     * @return stdClass
     */
    private function generateAntiFloodSection(bool $enabled, int $seconds, int $messages_number, int $ban_minutes, string $ban_message): stdClass
    {
        return (object)['enabled' => $enabled,
            'seconds' => $seconds,
            'messages_number' => $messages_number,
            'ban_minutes' => $ban_minutes,
            'ban_message' => $ban_message
        ];
    }

    /**
     * @param stdClass $antiflood_section
     * @return bool
     */
    private function validateAntiFloodSection(stdClass $antiflood_section): bool
    {
        if (!is_bool($antiflood_section->enabled) or !is_int($antiflood_section->seconds) or !is_int($antiflood_section->messages_number) or !is_int($antiflood_section->ban_minutes) or !is_string($antiflood_section->ban_message)) return false;
        return true;
    }

    /**
     * @param $settings
     * @return bool
     * @throws SettingsProviderException
     */
    function saveSettings($settings): bool
    {
        if (!$this->validateSettings($settings)) throw new settingsProviderException("Invalid settings provided");
        $result = file_put_contents($this->settings_path, json_encode($settings));
        if (false == $result) return false;
        return true;
    }

    /**
     * @param stdClass $settings
     * @return bool
     */
    function validateSettings(stdClass $settings): bool
    {
        if (empty($settings) or !$this->validateGeneralSection($settings->general) or !$this->validateTelegramSection($settings->telegram) or !$this->validateMaintenanceSection($settings->maintenance) or !$this->validateAntiFloodSection($settings->antiflood)) return false;
        return true;
    }

    /**
     * @return stdClass
     * @throws SettingsProviderException
     */
    function loadSettings(): stdClass
    {
        $settings_file = file_get_contents($this->settings_path);
        if (false == $settings_file or empty($settings_file)) throw new settingsProviderException("Could not load settings");
        $settings = json_decode($settings_file);
        if (!$this->validateSettings($settings)) throw new settingsProviderException("Settings file invalid");
        return $settings;
    }

}