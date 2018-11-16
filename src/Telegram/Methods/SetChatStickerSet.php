<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class SetChatStickerSet
 * @package TelegramBot\Telegram\Methods
 */
class SetChatStickerSet implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setChatStickerSet";
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
    private $chat_id;
    /**
     * @var string
     */
    private $sticker_set_name;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetChatStickerSet constructor.
     * @param string $chat_id
     * @param string $sticker_set_name
     */
    function __construct(string $chat_id, string $sticker_set_name)
    {
        $this->chat_id = $chat_id;
        $this->sticker_set_name = $sticker_set_name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'sticker_set_name' => $this->sticker_set_name
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