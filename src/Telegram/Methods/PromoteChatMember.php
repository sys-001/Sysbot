<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class PromoteChatMember
 * @package TelegramBot\Telegram\Methods
 */
class PromoteChatMember implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "promoteChatMember";
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
     * @var bool
     */
    private $can_change_info;
    /**
     * @var bool
     */
    private $can_post_messages;
    /**
     * @var bool
     */
    private $can_edit_messages;
    /**
     * @var bool
     */
    private $can_delete_messages;
    /**
     * @var bool
     */
    private $can_invite_users;
    /**
     * @var bool
     */
    private $can_restrict_members;
    /**
     * @var bool
     */
    private $can_pin_messages;
    /**
     * @var bool
     */
    private $can_promote_members;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * PromoteChatMember constructor.
     * @param string $chat_id
     * @param int $user_id
     * @param bool $can_change_info
     * @param bool $can_post_messages
     * @param bool|null $can_edit_messages
     * @param bool|null $can_delete_messages
     * @param bool $can_invite_users
     * @param bool $can_restrict_members
     * @param bool $can_pin_messages
     * @param bool $can_promote_members
     */
    function __construct(string $chat_id, int $user_id, bool $can_change_info = false, bool $can_post_messages = false, bool $can_edit_messages = null, bool $can_delete_messages = null, bool $can_invite_users = false, bool $can_restrict_members = false, bool $can_pin_messages = false, bool $can_promote_members = false)
    {
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
        $this->can_change_info = $can_change_info;
        $this->can_post_messages = $can_post_messages;
        $this->can_edit_messages = $can_edit_messages;
        $this->can_delete_messages = $can_delete_messages;
        $this->can_invite_users = $can_invite_users;
        $this->can_restrict_members = $can_restrict_members;
        $this->can_pin_messages = $can_pin_messages;
        $this->can_promote_members = $can_promote_members;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'can_change_info' => $this->can_change_info,
            'can_post_messages' => $this->can_post_messages,
            'can_edit_messages' => $this->can_edit_messages,
            'can_delete_messages' => $this->can_delete_messages,
            'can_invite_users' => $this->can_invite_users,
            'can_restrict_members' => $this->can_restrict_members,
            'can_pin_messages' => $this->can_pin_messages,
            'can_promote_members' => $this->can_promote_members
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