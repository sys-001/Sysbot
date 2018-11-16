<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;

/**
 * Class UploadStickerFile
 * @package TelegramBot\Telegram\Methods
 */
class UploadStickerFile implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "uploadStickerFile";
    /**
     *
     */
    private const RESULT_TYPE = "File";
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var int
     */
    private $user_id;
    /**
     * @var InputFile
     */
    private $png_sticker;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * UploadStickerFile constructor.
     * @param int $user_id
     * @param InputFile $png_sticker
     */
    function __construct(int $user_id, InputFile $png_sticker)
    {
        if ($png_sticker->is_local) $this->multipart = true;
        $this->user_id = $user_id;
        $this->png_sticker = $png_sticker->input_file;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'user_id' => $this->user_id,
            'png_sticker' => $this->png_sticker
        ];
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return self::METHOD_NAME;
    }

    /**
     * @return bool
     */
    public function isMultipart(): bool
    {
        return $this->multipart;
    }

    /**
     * @return array
     */
    public function getResultParams(): array
    {
        return [
            'type' => self::RESULT_TYPE,
            'multiple' => self::MULTIPLE_RESULTS
        ];
    }

}