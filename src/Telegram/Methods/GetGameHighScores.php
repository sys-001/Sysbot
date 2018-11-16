<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Exception\TelegramBotException;

/**
 * Class GetGameHighScores
 * @package TelegramBot\Telegram\Methods
 */
class GetGameHighScores implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "getGameHighScores";
    /**
     *
     */
    private const RESULT_TYPE = "GameHighScore";
    /**
     *
     */
    private const MULTIPLE_RESULTS = true;

    /**
     * @var int
     */
    private $user_id;
    /**
     * @var null
     */
    private $chat_id;
    /**
     * @var null
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
     * GetGameHighScores constructor.
     * @param int $user_id
     * @param null|string $chat_id
     * @param int|null $message_id
     * @param null|string $inline_message_id
     * @throws TelegramBotException
     */
    function __construct(int $user_id, ?string $chat_id, ?int $message_id, ?string $inline_message_id)
    {
        if (!isset($inline_message_id)) {
            if (isset($chat_id) and isset($message_id)) {
                $this->chat_id = $chat_id;
                $this->message_id = $message_id;
            } else {
                throw new TelegramBotException("Too few arguments");
            }
        } else {
            $this->chat_id = null;
            $this->message_id = null;
            $this->inline_message_id = $inline_message_id;
        }
        $this->user_id = $user_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'user_id' => $this->user_id,
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