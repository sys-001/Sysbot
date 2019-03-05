<?php

namespace TelegramBot\Telegram\Types;

/**
 * Interface InputMediaInterface
 * @package TelegramBot\Telegram\Types
 */
interface InputMediaInterface
{

    /**
     * @param null|\stdClass $input_media
     * @return null|InputMediaInterface
     */
    public static function parseInputMedia(?\stdClass $input_media): ?self;

    /**
     * @param null|string $type
     * @return InputMediaInterface
     */
    public function setType(?string $type): self;

    /**
     * @param null|string $media
     * @return InputMediaInterface
     */
    public function setMedia(?string $media): self;

    /**
     * @param null|string $caption
     * @return InputMediaInterface
     */
    public function setCaption(?string $caption): self;

    /**
     * @param null|string $parse_mode
     * @return InputMediaInterface
     */
    public function setParseMode(?string $parse_mode): self;

}