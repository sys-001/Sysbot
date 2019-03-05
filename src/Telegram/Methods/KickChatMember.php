<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class KickChatMember
 * @package TelegramBot\Telegram\Methods
 */
class KickChatMember implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "kickChatMember";
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
     * @var int
     */
    private $until_date;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * KickChatMember constructor.
     * @param string $chat_id
     * @param int $user_id
     * @param int|null $until_date
     */
    function __construct(string $chat_id, int $user_id, int $until_date = null)
    {
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
        $this->until_date = $until_date;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'until_date' => $this->until_date
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