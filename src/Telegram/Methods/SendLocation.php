<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\ReplyMarkupInterface;

/**
 * Class SendLocation
 * @package TelegramBot\Telegram\Methods
 */
class SendLocation implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendLocation";
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
     * @var float
     */
    private $latitude;
    /**
     * @var float
     */
    private $longitude;
    /**
     * @var int
     */
    private $live_period;
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
     * SendLocation constructor.
     * @param string $chat_id
     * @param float $latitude
     * @param float $longitude
     * @param int|null $live_period
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param ReplyMarkupInterface|null $reply_markup
     */
    function __construct(string $chat_id, float $latitude, float $longitude, int $live_period = null, bool $disable_notification = false, int $reply_to_message_id = null, ReplyMarkupInterface $reply_markup = null)
    {
        $this->chat_id = $chat_id;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->live_period = $live_period;
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
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'live_period' => $this->live_period,
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