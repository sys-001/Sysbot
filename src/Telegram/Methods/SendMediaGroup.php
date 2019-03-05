<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class SendMediaGroup
 * @package TelegramBot\Telegram\Methods
 */
class SendMediaGroup implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendMediaGroup";
    /**
     *
     */
    private const RESULT_TYPE = "Message";
    /**
     *
     */
    private const MULTIPLE_RESULTS = true;

    /**
     * @var string
     */
    private $chat_id;
    /**
     * @var array
     */
    private $media;
    /**
     * @var bool
     */
    private $disable_notification;
    /**
     * @var int
     */
    private $reply_to_message_id;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SendMediaGroup constructor.
     * @param string $chat_id
     * @param array $media
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     */
    function __construct(string $chat_id, array $media, bool $disable_notification = false, int $reply_to_message_id = null)
    {
        $this->chat_id = $chat_id;
        foreach ($media as $media_file) {
            if ($media_file->is_multipart) $this->multipart = true;
        }
        $this->media = $media;
        $this->disable_notification = $disable_notification;
        $this->reply_to_message_id = $reply_to_message_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'media' => $this->media,
            'disable_notification' => $this->disable_notification,
            'reply_to_message_id' => $this->reply_to_message_id
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