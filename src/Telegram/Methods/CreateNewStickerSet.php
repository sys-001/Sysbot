<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;
use TelegramBot\Telegram\Types\MaskPosition;

/**
 * Class CreateNewStickerSet
 * @package TelegramBot\Telegram\Methods
 */
class CreateNewStickerSet implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "createNewStickerSet";
    /**
     *
     */
    private const RESULT_TYPE = null;
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var int
     */
    private $user_id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $title;
    /**
     * @var InputFile
     */
    private $png_sticker;
    /**
     * @var string
     */
    private $emojis;
    /**
     * @var bool
     */
    private $contains_masks;
    /**
     * @var MaskPosition
     */
    private $mask_position;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * CreateNewStickerSet constructor.
     * @param int $user_id
     * @param string $name
     * @param string $title
     * @param InputFile $png_sticker
     * @param string $emojis
     * @param bool $contains_masks
     * @param MaskPosition|null $mask_position
     */
    function __construct(int $user_id, string $name, string $title, InputFile $png_sticker, string $emojis, bool $contains_masks = false, MaskPosition $mask_position = null)
    {
        if ($png_sticker->is_local) $this->multipart = true;
        $this->user_id = $user_id;
        $this->name = $name;
        $this->title = $title;
        $this->png_sticker = $png_sticker->input_file;
        $this->emojis = $emojis;
        $this->contains_masks = $contains_masks;
        $this->mask_position = $mask_position;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'title' => $this->title,
            'png_sticker' => $this->png_sticker,
            'emojis' => $this->emojis,
            'contains_masks' => $this->contains_masks,
            'mask_position' => $this->mask_position
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