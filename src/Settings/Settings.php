<?php

namespace TelegramBot\Settings;

/**
 * Class Settings
 * @package TelegramBot\Settings
 */
class Settings
{
    /**
     * @var Sections\GeneralSection
     */
    private $general_section;
    /**
     * @var Sections\TelegramSection
     */
    private $telegram_section;
    /**
     * @var Sections\MaintenanceSection
     */
    private $maintenance_section;
    /**
     * @var Sections\AntifloodSection
     */
    private $antiflood_section;

    /**
     * Settings constructor.
     * @param Sections\GeneralSection $general_section
     * @param Sections\TelegramSection $telegram_section
     * @param Sections\MaintenanceSection $maintenance_section
     * @param Sections\AntifloodSection $antiflood_section
     */
    public function __construct(
        Sections\GeneralSection $general_section,
        Sections\TelegramSection $telegram_section,
        Sections\MaintenanceSection $maintenance_section,
        Sections\AntifloodSection $antiflood_section
    )
    {
        $this->general_section = $general_section;
        $this->telegram_section = $telegram_section;
        $this->maintenance_section = $maintenance_section;
        $this->antiflood_section = $antiflood_section;
    }

    /**
     * @return mixed
     */
    public function getGeneralSection(): Sections\GeneralSection
    {
        return $this->general_section;
    }

    /**
     * @param mixed $general_section
     * @return Settings
     */
    public function setGeneralSection(Sections\GeneralSection $general_section): self
    {
        $this->general_section = $general_section;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTelegramSection(): Sections\TelegramSection
    {
        return $this->telegram_section;
    }

    /**
     * @param mixed $telegram_section
     * @return Settings
     */
    public function setTelegramSection(Sections\TelegramSection $telegram_section): self
    {
        $this->telegram_section = $telegram_section;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaintenanceSection(): Sections\MaintenanceSection
    {
        return $this->maintenance_section;
    }

    /**
     * @param mixed $maintenance_section
     * @return Settings
     */
    public function setMaintenanceSection(Sections\MaintenanceSection $maintenance_section): self
    {
        $this->maintenance_section = $maintenance_section;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAntifloodSection(): Sections\AntifloodSection
    {
        return $this->antiflood_section;
    }

    /**
     * @param mixed $antiflood_section
     * @return Settings
     */
    public function setAntifloodSection(Sections\AntifloodSection $antiflood_section): self
    {
        $this->antiflood_section = $antiflood_section;
        return $this;
    }
}