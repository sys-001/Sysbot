<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;

/**
 * Class DeleteStickerFromSet
 * @package TelegramBot\Telegram\Methods
 */
class DeleteStickerFromSet implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "deleteStickerFromSet";
    /**
     *
     */
    private const RESULT_TYPE = null;
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var string
     */
    private $sticker;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * DeleteStickerFromSet constructor.
     * @param InputFile $sticker
     */
    function __construct(InputFile $sticker)
    {
        $this->sticker = $sticker->input_file;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'sticker' => $this->sticker
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