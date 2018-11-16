<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class SetGameScore
 * @package TelegramBot\Telegram\Methods
 */
class SetGameScore implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setGameScore";
    /**
     *
     */
    private const RESULT_TYPE = null;
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var int
     */
    private $user_id;
    /**
     * @var int
     */
    private $score;
    /**
     * @var bool
     */
    private $force;
    /**
     * @var bool
     */
    private $disable_edit_message;
    /**
     * @var null|string
     */
    private $chat_id;
    /**
     * @var int|null
     */
    private $message_id;
    /**
     * @var null|string
     */
    private $inline_message_id;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetGameScore constructor.
     * @param int $user_id
     * @param int $score
     * @param null|string $chat_id
     * @param int|null $message_id
     * @param null|string $inline_message_id
     * @param bool $force
     * @param bool $disable_edit_message
     */
    function __construct(int $user_id, int $score, ?string $chat_id, ?int $message_id, ?string $inline_message_id, bool $force = false, bool $disable_edit_message = false)
    {
        $this->user_id = $user_id;
        $this->score = $score;
        $this->force = $force;
        $this->disable_edit_message = $disable_edit_message;
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
        $this->inline_message_id = $inline_message_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'user_id' => $this->user_id,
            'score' => $this->score,
            'force' => $this->force,
            'disable_edit_message' => $this->disable_edit_message,
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id,
            'inline_message_id' => $this->inline_message_id
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