<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputMediaPhoto
 * @package TelegramBot\Telegram\Types
 */
class InputMediaPhoto extends InputMedia
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
     * @param null|\stdClass $input_media_photo
     * @return null|InputMediaPhoto
     */
    public static function parseInputMedia(?\stdClass $input_media_photo): ?InputMediaInterface
    {
        if (is_null($input_media_photo)) {
            return null;
        }
        return (new self())
            ->setType($input_media_photo->type ?? null)
            ->setMedia(InputFile::parseInputFile($input_media_photo->media ?? null))
            ->setCaption($input_media_photo->caption ?? null)
            ->setParseMode($input_media_photo->parse_mode ?? null);
    }

}