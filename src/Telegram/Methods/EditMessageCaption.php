<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InlineKeyboardMarkup;

/**
 * Class EditMessageCaption
 * @package TelegramBot\Telegram\Methods
 */
class EditMessageCaption implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "editMessageCaption";
    /**
     *
     */
    private const RESULT_TYPE = "Message";
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

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
     * @var string
     */
    private $caption;
    /**
     * @var string
     */
    private $parse_mode;
    /**
     * @var InlineKeyboardMarkup
     */
    private $reply_markup;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * EditMessageCaption constructor.
     * @param null|string $chat_id
     * @param int|null $message_id
     * @param null|string $inline_message_id
     * @param string|null $caption
     * @param string|null $parse_mode
     * @param InlineKeyboardMarkup|null $reply_markup
     */
    function __construct(
        ?string $chat_id,
        ?int $message_id,
        ?string $inline_message_id,
        string $caption = null,
        string $parse_mode = null,
        InlineKeyboardMarkup $reply_markup = null
    ) {
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
        $this->inline_message_id = $inline_message_id;
        $this->caption = $caption;
        $this->parse_mode = $parse_mode;
        $this->reply_markup = $reply_markup;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id,
            'inline_message_id' => $this->inline_message_id,
            'caption' => $this->caption,
            'parse_mode' => $this->parse_mode,
            'reply_markup' => $this->reply_markup
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