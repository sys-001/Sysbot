<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class SetChatTitle
 * @package TelegramBot\Telegram\Methods
 */
class SetChatTitle implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setChatTitle";
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
    private $title;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetChatTitle constructor.
     * @param string $chat_id
     * @param string $title
     */
    function __construct(string $chat_id, string $title)
    {
        $this->chat_id = $chat_id;
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'title' => $this->title
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