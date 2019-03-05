<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;
use TelegramBot\Telegram\Types\ReplyMarkupInterface;

/**
 * Class SendPhoto
 * @package TelegramBot\Telegram\Methods
 */
class SendPhoto implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendPhoto";
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
    private $photo;
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
     * @var ReplyMarkupInterface
     */
    private $reply_markup;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SendPhoto constructor.
     * @param string $chat_id
     * @param InputFile $photo
     * @param string|null $caption
     * @param string|null $parse_mode
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param ReplyMarkupInterface|null $reply_markup
     */
    function __construct(string $chat_id, InputFile $photo, string $caption = null, string $parse_mode = null, bool $disable_notification = false, int $reply_to_message_id = null, ReplyMarkupInterface $reply_markup = null)
    {
        if ($photo->is_local) $this->multipart = true;
        $this->chat_id = $chat_id;
        $this->photo = $photo->input_file;
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
            'photo' => $this->photo,
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