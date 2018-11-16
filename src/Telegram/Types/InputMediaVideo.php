<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputMediaVideo
 * @package TelegramBot\Telegram\Types
 */
class InputMediaVideo extends InputMedia
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
     * @var
     */
    public $supports_streaming;


    /**
     * @param null|\stdClass $input_media_video
     * @return null|InputMediaInterface
     */
    public static function parseInputMedia(?\stdClass $input_media_video): ?InputMediaInterface
    {
        if (is_null($input_media_video)) return null;
        return (new self())
            ->setThumb($input_media_video->thumb ?? null)
            ->setWidth($input_media_video->width ?? null)
            ->setHeight($input_media_video->height ?? null)
            ->setDuration($input_media_video->duration ?? null)
            ->setSupportsStreaming($input_media_video->supports_streaming ?? null)
            ->setType($input_media_video->type ?? null)
            ->setMedia(InputFile::parseInputFile($input_media_video->media ?? null))
            ->setCaption($input_media_video->caption ?? null)
            ->setParseMode($input_media_video->parse_mode ?? null);
    }

    /**
     * @param bool|null $supports_streaming
     * @return InputMediaVideo
     */
    public function setSupportsStreaming(?bool $supports_streaming): InputMediaInterface
    {
        $this->supports_streaming = $supports_streaming;
        return $this;
    }

    /**
     * @param int|null $duration
     * @return InputMediaVideo
     */
    public function setDuration(?int $duration): InputMediaInterface
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param int|null $height
     * @return InputMediaVideo
     */
    public function setHeight(?int $height): InputMediaInterface
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int|null $width
     * @return InputMediaVideo
     */
    public function setWidth(?int $width): InputMediaInterface
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param null|InputFile $thumb
     * @return InputMediaVideo
     */
    public function setThumb(?InputFile $thumb): InputMediaInterface
    {
        $this->thumb = $thumb;
        return $this;
    }

}