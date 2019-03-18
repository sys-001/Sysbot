<?php


namespace TelegramBot\Addons;


/**
 * Class DefaultAddon
 * @package TelegramBot\Addons
 */
abstract class DefaultAddon implements AddonInterface
{

    /**
     * @var
     */
    protected $callback;
    /**
     * @var
     */
    private $update_paths = [];

    /**
     * @return \Closure
     */
    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    /**
     * @return array
     */
    public function getUpdatePaths(): array
    {
        return $this->update_paths;
    }
}