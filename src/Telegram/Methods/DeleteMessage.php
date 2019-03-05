<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class DeleteMessage
 * @package TelegramBot\Telegram\Methods
 */
class DeleteMessage implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "deleteMessage";
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
     * @var int
     */
    private $message_id;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * DeleteMessage constructor.
     * @param string $chat_id
     * @param int $message_id
     */
    function __construct(string $chat_id, int $message_id)
    {
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id
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