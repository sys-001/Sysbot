<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InlineKeyboardMarkup;
use TelegramBot\Telegram\Types\InputFile;

/**
 * Class SendAnimation
 * @package TelegramBot\Telegram\Methods
 */
class SendAnimation implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendAnimation";
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
    private $animation;
    /**
     * @var int
     */
    private $duration;
    /**
     * @var int
     */
    private $width;
    /**
     * @var int
     */
    private $height;
    /**
     * @var InputFile
     */
    private $thumb;
    /**
     * @var string
     */
    private $caption;
    /**
     * @var string
     */
    private $parse_mode;
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
     * SendAnimation constructor.
     * @param string $chat_id
     * @param InputFile $animation
     * @param int|null $duration
     * @param int|null $width
     * @param int|null $height
     * @param InputFile|null $thumb
     * @param string|null $caption
     * @param string|null $parse_mode
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param InlineKeyboardMarkup|null $reply_markup
     */
    function __construct(string $chat_id, InputFile $animation, int $duration = null, int $width = null, int $height = null, InputFile $thumb = null, string $caption = null, string $parse_mode = null, bool $disable_notification = false, int $reply_to_message_id = null, InlineKeyboardMarkup $reply_markup = null)
    {
        if ($animation->is_local or $thumb->is_local) $this->multipart = true;
        $this->chat_id = $chat_id;
        $this->animation = $animation->input_file;
        $this->duration = $duration;
        $this->width = $width;
        $this->height = $height;
        $this->thumb = $thumb->input_file;
        $this->caption = $caption;
        $this->parse_mode = $parse_mode;
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
            'animation' => $this->animation,
            'duration' => $this->duration,
            'width' => $this->width,
            'height' => $this->height,
            'thumb' => $this->thumb,
            'caption' => $this->caption,
            'parse_mode' => $this->parse_mode,
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