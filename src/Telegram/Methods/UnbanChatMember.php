<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class UnbanChatMember
 * @package TelegramBot\Telegram\Methods
 */
class UnbanChatMember implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "unbanChatMember";
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
    private $user_id;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * UnbanChatMember constructor.
     * @param string $chat_id
     * @param int $user_id
     */
    function __construct(string $chat_id, int $user_id)
    {
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id
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