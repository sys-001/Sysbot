<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;
use TelegramBot\Telegram\Types\ReplyMarkupInterface;

/**
 * Class SendVideoNote
 * @package TelegramBot\Telegram\Methods
 */
class SendVideoNote implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendVideoNote";
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
    private $video_note;
    /**
     * @var int
     */
    private $duration;
    /**
     * @var int
     */
    private $length;
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
     * @var ReplyMarkupInterface
     */
    private $reply_markup;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SendVideoNote constructor.
     * @param string $chat_id
     * @param InputFile $video_note
     * @param int|null $duration
     * @param int|null $length
     * @param InputFile|null $thumb
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param ReplyMarkupInterface|null $reply_markup
     */
    function __construct(string $chat_id, InputFile $video_note, int $duration = null, int $length = null, InputFile $thumb = null, bool $disable_notification = false, int $reply_to_message_id = null, ReplyMarkupInterface $reply_markup = null)
    {
        if ($video_note->is_local or $thumb->is_local) $this->multipart = true;
        $this->chat_id = $chat_id;
        $this->video_note = $video_note->input_file;
        $this->duration = $duration;
        $this->length = $length;
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
            'video_note' => $this->video_note,
            'duration' => $this->duration,
            'length' => $this->length,
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