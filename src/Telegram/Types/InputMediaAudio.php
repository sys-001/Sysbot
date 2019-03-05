<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputMediaAudio
 * @package TelegramBot\Telegram\Types
 */
class InputMediaAudio extends InputMedia
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
    public $duration;
    /**
     * @var
     */
    public $performer;
    /**
     * @var
     */
    public $title;


    /**
     * @param null|\stdClass $input_media_audio
     * @return null|InputMediaAudio
     */
    public static function parseInputMedia(?\stdClass $input_media_audio): ?InputMediaInterface
    {
        if (is_null($input_media_audio)) return null;
        return (new self())
            ->setThumb($input_media_audio->thumb ?? null)
            ->setDuration($input_media_audio->duration ?? null)
            ->setPerformer($input_media_audio->performer ?? null)
            ->setTitle($input_media_audio->title ?? null)
            ->setType($input_media_audio->type ?? null)
            ->setMedia(InputFile::parseInputFile($input_media_audio->media ?? null))
            ->setCaption($input_media_audio->caption ?? null)
            ->setParseMode($input_media_audio->parse_mode ?? null);
    }

    /**
     * @param null|string $title
     * @return InputMediaAudio
     */
    public function setTitle(?string $title): InputMediaInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $performer
     * @return InputMediaAudio
     */
    public function setPerformer(?string $performer): InputMediaInterface
    {
        $this->performer = $performer;
        return $this;
    }

    /**
     * @param int|null $duration
     * @return InputMediaAudio
     */
    public function setDuration(?int $duration): InputMediaInterface
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param null|InputFile $thumb
     * @return InputMediaAudio
     */
    public function setThumb(?InputFile $thumb): InputMediaInterface
    {
        $this->thumb = $thumb;
        return $this;
    }

}