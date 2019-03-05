<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ChatMember
 * @package TelegramBot\Telegram\Types
 */
class ChatMember
{

    /**
     * @var
     */
    public $user;
    /**
     * @var
     */
    public $status;
    /**
     * @var
     */
    public $until_date;
    /**
     * @var
     */
    public $can_be_edited;
    /**
     * @var
     */
    public $can_change_info;
    /**
     * @var
     */
    public $can_post_messages;
    /**
     * @var
     */
    public $can_edit_messages;
    /**
     * @var
     */
    public $can_delete_messages;
    /**
     * @var
     */
    public $can_invite_users;
    /**
     * @var
     */
    public $can_restrict_members;
    /**
     * @var
     */
    public $can_pin_messages;
    /**
     * @var
     */
    public $can_promote_members;
    /**
     * @var
     */
    public $can_send_messages;
    /**
     * @var
     */
    public $can_send_media_messages;
    /**
     * @var
     */
    public $can_send_other_messages;
    /**
     * @var
     */
    public $can_add_web_page_previews;


    /**
     * @param null|\stdClass $chat_member
     * @return null|ChatMember
     */
    public static function parseChatMember(?\stdClass $chat_member): ?self
    {
        if (is_null($chat_member)) return null;
        return (new self())
            ->setUser(User::parseUser($chat_member->user ?? null))
            ->setStatus($chat_member->status ?? null)
            ->setUntilDate($chat_member->until_date ?? null)
            ->setCanBeEdited($chat_member->can_be_edited ?? null)
            ->setCanChangeInfo($chat_member->can_change_info ?? null)
            ->setCanPostMessages($chat_member->can_post_messages ?? null)
            ->setCanEditMessages($chat_member->can_edit_messages ?? null)
            ->setCanDeleteMessages($chat_member->can_delete_messages ?? null)
            ->setCanInviteUsers($chat_member->can_invite_users ?? null)
            ->setCanRestrictMembers($chat_member->can_restrict_members ?? null)
            ->setCanPinMessages($chat_member->can_pin_messages ?? null)
            ->setCanPromoteMembers($chat_member->can_promote_members ?? null)
            ->setCanSendMessages($chat_member->can_send_messages ?? null)
            ->setCanSendMediaMessages($chat_member->can_send_media_messages ?? null)
            ->setCanSendOtherMessages($chat_member->can_send_other_messages ?? null)
            ->setCanAddWebPagePreviews($chat_member->can_add_web_page_previews ?? null);
    }

    /**
     * @param bool|null $can_add_web_page_previews
     * @return ChatMember
     */
    public function setCanAddWebPagePreviews(?bool $can_add_web_page_previews): self
    {
        $this->can_add_web_page_previews = $can_add_web_page_previews;
        return $this;
    }

    /**
     * @param bool|null $can_send_other_messages
     * @return ChatMember
     */
    public function setCanSendOtherMessages(?bool $can_send_other_messages): self
    {
        $this->can_send_other_messages = $can_send_other_messages;
        return $this;
    }

    /**
     * @param bool|null $can_send_media_messages
     * @return ChatMember
     */
    public function setCanSendMediaMessages(?bool $can_send_media_messages): self
    {
        $this->can_send_media_messages = $can_send_media_messages;
        return $this;
    }

    /**
     * @param bool|null $can_send_messages
     * @return ChatMember
     */
    public function setCanSendMessages(?bool $can_send_messages): self
    {
        $this->can_send_messages = $can_send_messages;
        return $this;
    }

    /**
     * @param bool|null $can_promote_members
     * @return ChatMember
     */
    public function setCanPromoteMembers(?bool $can_promote_members): self
    {
        $this->can_promote_members = $can_promote_members;
        return $this;
    }

    /**
     * @param bool|null $can_pin_messages
     * @return ChatMember
     */
    public function setCanPinMessages(?bool $can_pin_messages): self
    {
        $this->can_pin_messages = $can_pin_messages;
        return $this;
    }

    /**
     * @param bool|null $can_restrict_members
     * @return ChatMember
     */
    public function setCanRestrictMembers(?bool $can_restrict_members): self
    {
        $this->can_restrict_members = $can_restrict_members;
        return $this;
    }

    /**
     * @param bool|null $can_invite_users
     * @return ChatMember
     */
    public function setCanInviteUsers(?bool $can_invite_users): self
    {
        $this->can_invite_users = $can_invite_users;
        return $this;
    }

    /**
     * @param bool|null $can_delete_messages
     * @return ChatMember
     */
    public function setCanDeleteMessages(?bool $can_delete_messages): self
    {
        $this->can_delete_messages = $can_delete_messages;
        return $this;
    }

    /**
     * @param bool|null $can_edit_messages
     * @return ChatMember
     */
    public function setCanEditMessages(?bool $can_edit_messages): self
    {
        $this->can_edit_messages = $can_edit_messages;
        return $this;
    }

    /**
     * @param bool|null $can_post_messages
     * @return ChatMember
     */
    public function setCanPostMessages(?bool $can_post_messages): self
    {
        $this->can_post_messages = $can_post_messages;
        return $this;
    }

    /**
     * @param bool|null $can_change_info
     * @return ChatMember
     */
    public function setCanChangeInfo(?bool $can_change_info): self
    {
        $this->can_change_info = $can_change_info;
        return $this;
    }

    /**
     * @param bool|null $can_be_edited
     * @return ChatMember
     */
    public function setCanBeEdited(?bool $can_be_edited): self
    {
        $this->can_be_edited = $can_be_edited;
        return $this;
    }

    /**
     * @param int|null $until_date
     * @return ChatMember
     */
    public function setUntilDate(?int $until_date): self
    {
        $this->until_date = $until_date;
        return $this;
    }

    /**
     * @param null|string $status
     * @return ChatMember
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param null|User $user
     * @return ChatMember
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

}