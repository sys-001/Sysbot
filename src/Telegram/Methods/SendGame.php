<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InlineKeyboardMarkup;

/**
 * Class SendGame
 * @package TelegramBot\Telegram\Methods
 */
class SendGame implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendGame";
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
     * @var string
     */
    private $game_short_name;
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
     * SendGame constructor.
     * @param string $chat_id
     * @param string $game_short_name
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param InlineKeyboardMarkup|null $reply_markup
     */
    function __construct(string $chat_id, string $game_short_name, bool $disable_notification = false, int $reply_to_message_id = null, InlineKeyboardMarkup $reply_markup = null)
    {
        $this->chat_id = $chat_id;
        $this->game_short_name = $game_short_name;
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
            'game_short_name' => $this->game_short_name,
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