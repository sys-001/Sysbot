<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class SetChatDescription
 * @package TelegramBot\Telegram\Methods
 */
class SetChatDescription implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setChatDescription";
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
    private $description;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetChatDescription constructor.
     * @param string $chat_id
     * @param string|null $description
     */
    function __construct(string $chat_id, string $description = null)
    {
        $this->chat_id = $chat_id;
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'description' => $this->description
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