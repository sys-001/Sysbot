<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class DeleteChatStickerSet
 * @package TelegramBot\Telegram\Methods
 */
class DeleteChatStickerSet implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "deleteChatStickerSet";
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
     * @var bool
     */
    private $multipart = false;

    /**
     * DeleteChatStickerSet constructor.
     * @param string $chat_id
     */
    function __construct(string $chat_id)
    {
        $this->chat_id = $chat_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id
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