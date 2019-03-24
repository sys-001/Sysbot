<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InlineKeyboardMarkup;
use TelegramBot\Telegram\Types\InputFile;

/**
 * Class SendAudio
 * @package TelegramBot\Telegram\Methods
 */
class SendAudio implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendAudio";
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
     * @var InputFile
     */
    private $audio;
    /**
     * @var string
     */
    private $caption;
    /**
     * @var string
     */
    private $parse_mode;
    /**
     * @var int
     */
    private $duration;
    /**
     * @var string
     */
    private $performer;
    /**
     * @var string
     */
    private $title;
    /**
     * @var InputFile
     */
    private $thumb;
    /**
     * @var bool
     */
    private $disable_notification;
    /**
     * @var int
     */
    private $reply_to_message_id;
    /**
     * @var InlineKeyboardMarkup
     */
    private $reply_markup;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SendAudio constructor.
     * @param string $chat_id
     * @param InputFile $audio
     * @param string|null $caption
     * @param string|null $parse_mode
     * @param int|null $duration
     * @param string|null $performer
     * @param string|null $title
     * @param InputFile|null $thumb
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param InlineKeyboardMarkup|null $reply_markup
     */
    function __construct(
        string $chat_id,
        InputFile $audio,
        string $caption = null,
        string $parse_mode = null,
        int $duration = null,
        string $performer = null,
        string $title = null,
        InputFile $thumb = null,
        bool $disable_notification = false,
        int $reply_to_message_id = null,
        InlineKeyboardMarkup $reply_markup = null
    ) {
        if ($audio->is_local or $thumb->is_local) {
            $this->multipart = true;
        }
        $this->chat_id = $chat_id;
        $this->audio = $audio->input_file;
        $this->caption = $caption;
        $this->parse_mode = $parse_mode;
        $this->duration = $duration;
        $this->performer = $performer;
        $this->title = $title;
        $this->thumb = $thumb->input_file;
        $this->disable_notification = $disable_notification;
        $this->reply_to_message_id = $reply_to_message_id;
        $this->reply_markup = $reply_markup;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'audio' => $this->audio,
            'caption' => $this->caption,
            'parse_mode' => $this->parse_mode,
            'duration' => $this->duration,
            'performer' => $this->performer,
            'title' => $this->title,
            'thumb' => $this->thumb,
            'disable_notification' => $this->disable_notification,
            'reply_to_message_id' => $this->reply_to_message_id,
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