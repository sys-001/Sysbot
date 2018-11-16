<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputMediaAnimation
 * @package TelegramBot\Telegram\Types
 */
class InputMediaAnimation extends InputMedia
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
    public $thumb;
    /**
     * @var
     */
    public $caption;
    /**
     * @var
     */
    public $parse_mode;
    /**
     * @var
     */
    public $width;
    /**
     * @var
     */
    public $height;
    /**
     * @var
     */
    public $duration;


    /**
     * @param null|\stdClass $input_media_animation
     * @return null|InputMediaAnimation
     */
    public static function parseInputMedia(?\stdClass $input_media_animation): ?InputMediaInterface
    {
        if (is_null($input_media_animation)) return null;
        return (new self())
            ->setThumb($input_media_animation->thumb ?? null)
            ->setWidth($input_media_animation->width ?? null)
            ->setHeight($input_media_animation->height ?? null)
            ->setDuration($input_media_animation->duration ?? null)
            ->setType($input_media_animation->type ?? null)
            ->setMedia(InputFile::parseInputFile($input_media_animation->media ?? null))
            ->setCaption($input_media_animation->caption ?? null)
            ->setParseMode($input_media_animation->parse_mode ?? null);
    }

    /**
     * @param int|null $duration
     * @return InputMediaAnimation
     */
    public function setDuration(?int $duration): InputMediaInterface
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param int|null $height
     * @return InputMediaAnimation
     */
    public function setHeight(?int $height): InputMediaInterface
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int|null $width
     * @return InputMediaAnimation
     */
    public function setWidth(?int $width): InputMediaInterface
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param null|PhotoSize $thumb
     * @return InputMediaAnimation
     */
    public function setThumb(?PhotoSize $thumb): InputMediaInterface
    {
        $this->thumb = $thumb;
        return $this;
    }

}