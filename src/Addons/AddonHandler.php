<?php

namespace TelegramBot\Addons;


/**
 * Class AddonHandler
 * @package TelegramBot\Addons
 */
class AddonHandler
{

    /**
     * @var array
     */
    private $addons = [];

    /**
     * AddonHandler constructor.
     */
    function __construct()
    {
        if (extension_loaded("redis")) $this->addons[] = new AntifloodAddon();
    }

    /**
     * @return array
     */
    public function getAddons(): array
    {
        return $this->addons;
    }

}