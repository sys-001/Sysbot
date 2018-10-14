<?php

namespace TelegramBot\Types\Settings\Sections;

use TelegramBot\Types\Settings\Handlers\AdminHandler;

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
     * GeneralSection constructor.
     * @param AdminHandler $admin_handler
     * @param string $administration_password
     */
    public function __construct(AdminHandler $admin_handler, string $administration_password)
    {
        $this->admin_handler = $admin_handler;
        $this->administration_password = $administration_password;
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
        $this->administration_password = $administration_password;
        return $this;
    }

}