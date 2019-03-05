<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class PinChatMessage
 * @package TelegramBot\Telegram\Methods
 */
class PinChatMessage implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "pinChatMessage";
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
    private $disable_notification;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * PinChatMessage constructor.
     * @param string $chat_id
     * @param int $message_id
     * @param bool $disable_notification
     */
    function __construct(string $chat_id, int $message_id, bool $disable_notification = false)
    {
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
        $this->disable_notification = $disable_notification;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id,
            'disable_notification' => $this->disable_notification
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