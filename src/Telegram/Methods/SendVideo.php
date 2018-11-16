<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;
use TelegramBot\Telegram\Types\ReplyMarkupInterface;

/**
 * Class SendVideo
 * @package TelegramBot\Telegram\Methods
 */
class SendVideo implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendVideo";
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
    private $video;
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
    private $supports_streaming;
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
     * SendVideo constructor.
     * @param string $chat_id
     * @param InputFile $video
     * @param int|null $duration
     * @param int|null $width
     * @param int|null $height
     * @param InputFile|null $thumb
     * @param string|null $caption
     * @param string|null $parse_mode
     * @param bool $supports_streaming
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param ReplyMarkupInterface|null $reply_markup
     */
    function __construct(string $chat_id, InputFile $video, int $duration = null, int $width = null, int $height = null, InputFile $thumb = null, string $caption = null, string $parse_mode = null, bool $supports_streaming = false, bool $disable_notification = false, int $reply_to_message_id = null, ReplyMarkupInterface $reply_markup = null)
    {
        if ($video->is_local or $thumb->is_local) $this->multipart = true;
        $this->chat_id = $chat_id;
        $this->video = $video->input_file;
        $this->duration = $duration;
        $this->width = $width;
        $this->height = $height;
        $this->thumb = $thumb->input_file;
        $this->caption = $caption;
        $this->parse_mode = $parse_mode;
        $this->supports_streaming = $supports_streaming;
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
            'video' => $this->video,
            'duration' => $this->duration,
            'width' => $this->width,
            'height' => $this->height,
            'thumb' => $this->thumb,
            'caption' => $this->caption,
            'parse_mode' => $this->parse_mode,
            'supports_streaming' => $this->supports_streaming,
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