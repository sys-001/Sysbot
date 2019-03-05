<?php

namespace TelegramBot\Telegram\Types;

/**
 * Class InputMedia
 * @package TelegramBot\Telegram\Types
 */
abstract class InputMedia implements InputMediaInterface
{
    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $media;
    /**
     * @var
     */
    public $caption;
    /**
     * @var
     */
    public $parse_mode;

    /**
     * @param null|\stdClass $input_media
     * @return null|InputMedia
     */
    public static function parseInputMedia(?\stdClass $input_media): ?InputMediaInterface
    {
        if (empty($input_media->type)) {
            return null;
        }
        $class_name = sprintf('InputMedia%s', ucfirst($input_media->type));
        return call_user_func([$class_name, 'parseInputMedia'], $input_media);
    }

    /**
     * @param null|string $type
     * @return InputMedia
     */
    public function setType(?string $type): InputMediaInterface
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param null|string $media
     * @return InputMedia
     */
    public function setMedia(?string $media): InputMediaInterface
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InputMedia
     */
    public function setCaption(?string $caption): InputMediaInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InputMedia
     */
    public function setParseMode(?string $parse_mode): InputMediaInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

}