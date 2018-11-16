<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class ForwardMessage
 * @package TelegramBot\Telegram\Methods
 */
class ForwardMessage implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "forwardMessage";
    /**
     *
     */
    private const RESULT_TYPE = "Message";
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
    private $from_chat_id;
    /**
     * @var bool
     */
    private $disable_notification;
    /**
     * @var int
     */
    private $message_id;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * ForwardMessage constructor.
     * @param string $chat_id
     * @param string $from_chat_id
     * @param int $message_id
     * @param bool $disable_notification
     */
    function __construct(string $chat_id, string $from_chat_id, int $message_id, bool $disable_notification = false)
    {
        $this->chat_id = $chat_id;
        $this->from_chat_id = $from_chat_id;
        $this->disable_notification = $disable_notification;
        $this->message_id = $message_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'from_chat_id' => $this->from_chat_id,
            'disable_notification' => $this->disable_notification,
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