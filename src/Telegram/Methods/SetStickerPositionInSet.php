<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;

/**
 * Class SetStickerPositionInSet
 * @package TelegramBot\Telegram\Methods
 */
class SetStickerPositionInSet implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setStickerPositionInSet";
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
     * @var int
     */
    private $position;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetStickerPositionInSet constructor.
     * @param InputFile $sticker
     * @param int $position
     */
    function __construct(InputFile $sticker, int $position)
    {
        $this->sticker = $sticker->input_file;
        $this->position = $position;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'sticker' => $this->sticker,
            'position' => $this->position
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