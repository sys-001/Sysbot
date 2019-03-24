<?php

namespace TelegramBot\Settings\Sections;

use TelegramBot\Settings\Handlers\AdminHandler;

/**
 * Class GeneralSection
 * @package TelegramBot\Settings\Sections
 */
class GeneralSection implements SectionInterface
{
    /**
     * @var AdminHandler
     */
    private $admin_handler;

    /**
     * @var bool
     */
    private $check_ip;
    /**
     * @var string
     */
    private $default_locale;
    /**
     * @var string
     */
    private $default_locale_path;

    /**
     * GeneralSection constructor.
     * @param AdminHandler $admin_handler
     * @param bool $check_ip
     * @param string $default_locale
     * @param string $default_locale_path
     */
    public function __construct(
        AdminHandler $admin_handler,
        bool $check_ip,
        string $default_locale,
        string $default_locale_path
    ) {
        $this->admin_handler = $admin_handler;
        $this->check_ip = $check_ip;
        $this->default_locale = $default_locale;
        $this->default_locale_path = $default_locale_path;
    }

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->default_locale;
    }

    /**
     * @return string
     */
    public function getDefaultLocalePath(): string
    {
        return $this->default_locale_path;
    }

    /**
     * @return bool
     */
    public function getCheckIp(): bool
    {
        return $this->check_ip;
    }

    /**
     * @param bool $check_ip
     * @return GeneralSection
     */
    public function setCheckIp(bool $check_ip): GeneralSection
    {
        $this->check_ip = $check_ip;
        return $this;
    }

    /**
     * @return AdminHandler
     */
    public function getAdminHandler(): AdminHandler
    {
        return $this->admin_handler;
    }

    /**
     * @param AdminHandler $admin_handler
     * @return GeneralSection
     */
    public function setAdmins(AdminHandler $admin_handler): self
    {
        $this->admin_handler = $admin_handler;
        return $this;
    }

}