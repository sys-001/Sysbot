<?php

namespace TelegramBot\Addons;


/**
 * Interface AddonInterface
 * @package TelegramBot\Addons
 */
interface AddonInterface
{
    /**
     * @return \Closure
     */
    public function getCallback(): \Closure;

    /**
     * @return array
     */
    public function getUpdatePaths(): array;
}