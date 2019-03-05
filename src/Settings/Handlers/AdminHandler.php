<?php

namespace TelegramBot\Settings\Handlers;


/**
 * Class AdminHandler
 * @package TelegramBot\Settings\Handlers
 */
class AdminHandler
{
    /**
     * @var array
     */
    private $admins = [];

    /**
     * AdminHandler constructor.
     * @param array $admins
     */
    function __construct(array $admins)
    {
        $this->admins = $admins;
    }

    /**
     * @return array
     */
    public function getAdmins(): array
    {
        return $this->admins;
    }

    /**
     * @param int $admin
     * @return array
     */
    public function addAdmin(int $admin): array
    {
        if (!in_array($admin, $this->admins)) {
            $this->admins[] = $admin;
        }
        return $this->admins;
    }

    /**
     * @param array $admins
     * @return array
     */
    public function addAdmins(array $admins): array
    {
        array_map([$this, 'addAdmin'], $admins);
        return $this->admins;
    }

    /**
     * @param int $admin
     * @return array
     */
    public function remAdmin(int $admin): array
    {
        $this->admins = array_diff($this->admins, [$admin]);
        return $this->admins;
    }

    /**
     * @param array $admins
     * @return array
     */
    public function remAdmins(array $admins): array
    {
        array_map([$this, 'remAdmin'], $admins);
        return $this->admins;
    }

    /**
     * @return AdminHandler
     */
    public function resetAdmins(): self
    {
        $this->admins = [];
        return $this;
    }

    /**
     * @param int $admin
     * @return bool
     */
    public function isAdmin(int $admin): bool
    {
        return in_array($admin, $this->admins);
    }
}