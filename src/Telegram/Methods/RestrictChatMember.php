<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class RestrictChatMember
 * @package TelegramBot\Telegram\Methods
 */
class RestrictChatMember implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "restrictChatMember";
    /**
     *
     */
    private const RESULT_TYPE = null;
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var string
     */
    private $chat_id;
    /**
     * @var int
     */
    private $user_id;
    /**
     * @var int
     */
    private $until_date;
    /**
     * @var bool
     */
    private $can_send_messages;
    /**
     * @var bool
     */
    private $can_send_media_messages;
    /**
     * @var bool
     */
    private $can_send_other_messages;
    /**
     * @var bool
     */
    private $can_add_web_page_previews;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * RestrictChatMember constructor.
     * @param string $chat_id
     * @param int $user_id
     * @param int|null $until_date
     * @param bool $can_send_messages
     * @param bool $can_send_media_messages
     * @param bool $can_send_other_messages
     * @param bool $can_add_web_page_previews
     */
    function __construct(string $chat_id, int $user_id, int $until_date = null, bool $can_send_messages = false, bool $can_send_media_messages = false, bool $can_send_other_messages = false, bool $can_add_web_page_previews = false)
    {
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
        $this->until_date = $until_date;
        $this->can_send_messages = $can_send_messages;
        $this->can_send_media_messages = $can_send_media_messages;
        $this->can_send_other_messages = $can_send_other_messages;
        $this->can_add_web_page_previews = $can_add_web_page_previews;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'until_date' => $this->until_date,
            'can_send_messages' => $this->can_send_messages,
            'can_send_media_messages' => $this->can_send_media_messages,
            'can_send_other_messages' => $this->can_send_other_messages,
            'can_add_web_page_previews' => $this->can_add_web_page_previews
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