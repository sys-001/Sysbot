<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class SendChatAction
 * @package TelegramBot\Telegram\Methods
 */
class SendChatAction implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendChatAction";
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
    private $action;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SendChatAction constructor.
     * @param string $chat_id
     * @param string $action
     */
    function __construct(string $chat_id, string $action)
    {
        $this->chat_id = $chat_id;
        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'action' => $this->action
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