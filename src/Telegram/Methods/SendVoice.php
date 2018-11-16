<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;
use TelegramBot\Telegram\Types\ReplyMarkupInterface;

/**
 * Class SendVoice
 * @package TelegramBot\Telegram\Methods
 */
class SendVoice implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendVoice";
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
    private $voice;
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
     * @var bool
     */
    private $disable_notification;
    /**
     * @var int
     */
    private $reply_to_message_id;
    /**
     * @var ReplyMarkupInterface
     */
    private $reply_markup;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SendVoice constructor.
     * @param string $chat_id
     * @param InputFile $voice
     * @param string|null $caption
     * @param string|null $parse_mode
     * @param int|null $duration
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param ReplyMarkupInterface|null $reply_markup
     */
    function __construct(string $chat_id, InputFile $voice, string $caption = null, string $parse_mode = null, int $duration = null, bool $disable_notification = false, int $reply_to_message_id = null, ReplyMarkupInterface $reply_markup = null)
    {
        if ($voice->is_local) $this->multipart = true;
        $this->chat_id = $chat_id;
        $this->voice = $voice->input_file;
        $this->caption = $caption;
        $this->parse_mode = $parse_mode;
        $this->duration = $duration;
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
            'voice' => $this->voice,
            'caption' => $this->caption,
            'parse_mode' => $this->parse_mode,
            'duration' => $this->duration,
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