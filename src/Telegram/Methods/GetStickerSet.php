<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class GetStickerSet
 * @package TelegramBot\Telegram\Methods
 */
class GetStickerSet implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "getStickerSet";
    /**
     *
     */
    private const RESULT_TYPE = "StickerSet";
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * GetStickerSet constructor.
     * @param string $name
     */
    function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'name' => $this->name
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