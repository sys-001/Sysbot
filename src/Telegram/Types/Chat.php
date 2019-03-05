<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Chat
 * @package TelegramBot\Telegram\Types
 */
class Chat
{

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $username;
    /**
     * @var
     */
    public $first_name;
    /**
     * @var
     */
    public $last_name;
    /**
     * @var
     */
    public $all_members_are_administrators;
    /**
     * @var
     */
    public $photo;
    /**
     * @var
     */
    public $description;
    /**
     * @var
     */
    public $invite_link;
    /**
     * @var
     */
    public $pinned_message;
    /**
     * @var
     */
    public $sticker_set_name;
    /**
     * @var
     */
    public $can_set_sticker_set;

    /**
     * @param null|\stdClass $chat
     * @return null|Chat
     */
    public static function parseChat(?\stdClass $chat): ?self
    {
        if (is_null($chat)) return null;
        return (new self())
            ->setId($chat->id ?? null)
            ->setType($chat->type ?? null)
            ->setTitle($chat->title ?? null)
            ->setUsername($chat->username ?? null)
            ->setFirstName($chat->first_name ?? null)
            ->setLastName($chat->last_name ?? null)
            ->setAllMembersAreAdministrators($chat->all_members_are_administrators ?? null)
            ->setPhoto(ChatPhoto::parseChatPhoto($chat->photo ?? null))
            ->setDescription($chat->description ?? null)
            ->setInviteLink($chat->invite_link ?? null)
            ->setPinnedMessage($chat->pinned_message ?? null)
            ->setStickerSetName($chat->sticker_set_name ?? null)
            ->setCanSetStickerSet($chat->can_set_sticker_set ?? null);
    }

    /**
     * @param mixed $can_set_sticker_set
     * @return Chat
     */
    public function setCanSetStickerSet(?string $can_set_sticker_set): self
    {
        $this->can_set_sticker_set = $can_set_sticker_set;
        return $this;
    }

    /**
     * @param mixed $sticker_set_name
     * @return Chat
     */
    public function setStickerSetName(?string $sticker_set_name): self
    {
        $this->sticker_set_name = $sticker_set_name;
        return $this;
    }

    /**
     * @param mixed $pinned_message
     * @return Chat
     */
    public function setPinnedMessage(?Message $pinned_message): self
    {
        $this->pinned_message = $pinned_message;
        return $this;
    }

    /**
     * @param mixed $invite_link
     * @return Chat
     */
    public function setInviteLink(?string $invite_link): self
    {
        $this->invite_link = $invite_link;
        return $this;
    }

    /**
     * @param mixed $description
     * @return Chat
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param mixed $photo
     * @return Chat
     */
    public function setPhoto(?ChatPhoto $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @param mixed $all_members_are_administrators
     * @return Chat
     */
    public function setAllMembersAreAdministrators(?bool $all_members_are_administrators): self
    {
        $this->all_members_are_administrators = $all_members_are_administrators;
        return $this;
    }

    /**
     * @param mixed $last_name
     * @return Chat
     */
    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param mixed $first_name
     * @return Chat
     */
    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param mixed $username
     * @return Chat
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $title
     * @return Chat
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param mixed $type
     * @return Chat
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $id
     * @return Chat
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

}