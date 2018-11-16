<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputMediaDocument
 * @package TelegramBot\Telegram\Types
 */
class InputMediaDocument extends InputMedia
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
     * @param null|\stdClass $input_media_document
     * @return null|InputMediaDocument
     */
    public static function parseInputMedia(?\stdClass $input_media_document): ?InputMediaInterface
    {
        if (is_null($input_media_document)) return null;
        return (new self())
            ->setThumb($input_media_document->thumb ?? null)
            ->setType($input_media_document->type ?? null)
            ->setMedia(InputFile::parseInputFile($input_media_document->media ?? null))
            ->setCaption($input_media_document->caption ?? null)
            ->setParseMode($input_media_document->parse_mode ?? null);
    }

    /**
     * @param null|InputFile $thumb
     * @return InputMediaDocument
     */
    public function setThumb(?InputFile $thumb): InputMediaInterface
    {
        $this->thumb = $thumb;
        return $this;
    }

}