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
     * @var string
     */
    private $administration_password;

    /**
     * @var bool
     */
    private $check_ip;

    /**
     * GeneralSection constructor.
     * @param AdminHandler $admin_handler
     * @param string $administration_password
     * @param bool $check_ip
     */
    public function __construct(AdminHandler $admin_handler, string $administration_password, bool $check_ip)
    {
        $hash_info = password_get_info($administration_password);
        if (1 != $hash_info['algo']) {
            $administration_password = password_hash($administration_password, PASSWORD_DEFAULT);
        }
        $this->admin_handler = $admin_handler;
        $this->administration_password = $administration_password;
        $this->check_ip = $check_ip;
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

    /**
     * @return string
     */
    public function getAdministrationPassword(): string
    {
        return $this->administration_password;
    }

    /**
     * @param string $administration_password
     * @return GeneralSection
     */
    public function setAdministrationPassword(string $administration_password): self
    {
        $hash_info = password_get_info($administration_password);
        if (1 != $hash_info['algo']) {
            $administration_password = password_hash($administration_password, PASSWORD_DEFAULT);
        }
        $this->administration_password = $administration_password;
        return $this;
    }

}