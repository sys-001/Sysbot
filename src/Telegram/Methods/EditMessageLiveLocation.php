<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Exception\TelegramBotException;
use TelegramBot\Telegram\Types\InlineKeyboardMarkup;

/**
 * Class EditMessageLiveLocation
 * @package TelegramBot\Telegram\Methods
 */
class EditMessageLiveLocation implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "editMessageLiveLocation";
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
     * @var float
     */
    private $latitude;
    /**
     * @var float
     */
    private $longitude;
    /**
     * @var InlineKeyboardMarkup
     */
    private $reply_markup;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * EditMessageLiveLocation constructor.
     * @param null|string $chat_id
     * @param int|null $message_id
     * @param null|string $inline_message_id
     * @param float $latitude
     * @param float $longitude
     * @param InlineKeyboardMarkup|null $reply_markup
     * @throws TelegramBotException
     */
    function __construct(?string $chat_id, ?int $message_id, ?string $inline_message_id, float $latitude, float $longitude, InlineKeyboardMarkup $reply_markup = null)
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
        $this->latitude = $latitude;
        $this->longitude = $longitude;
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
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
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