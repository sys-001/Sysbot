<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Message
 * @package TelegramBot\Telegram\Types
 */
class Message
{


    /**
     * @var
     */
    public $message_id;
    /**
     * @var
     */
    public $from;
    /**
     * @var
     */
    public $date;
    /**
     * @var
     */
    public $chat;
    /**
     * @var
     */
    public $forward_from;
    /**
     * @var
     */
    public $forward_from_chat;
    /**
     * @var
     */
    public $forward_from_message_id;
    /**
     * @var
     */
    public $forward_signature;
    /**
     * @var
     */
    public $forward_date;
    /**
     * @var
     */
    public $reply_to_message;
    /**
     * @var
     */
    public $edit_date;
    /**
     * @var
     */
    public $media_group_id;
    /**
     * @var
     */
    public $author_signature;
    /**
     * @var
     */
    public $text;
    /**
     * @var
     */
    public $entities;
    /**
     * @var
     */
    public $caption_entities;
    /**
     * @var
     */
    public $audio;
    /**
     * @var
     */
    public $document;
    /**
     * @var
     */
    public $animation;
    /**
     * @var
     */
    public $game;
    /**
     * @var
     */
    public $photo;
    /**
     * @var
     */
    public $sticker;
    /**
     * @var
     */
    public $video;
    /**
     * @var
     */
    public $voice;
    /**
     * @var
     */
    public $video_note;
    /**
     * @var
     */
    public $caption;
    /**
     * @var
     */
    public $contact;
    /**
     * @var
     */
    public $location;
    /**
     * @var
     */
    public $venue;
    /**
     * @var
     */
    public $new_chat_members;
    /**
     * @var
     */
    public $left_chat_member;
    /**
     * @var
     */
    public $new_chat_title;
    /**
     * @var
     */
    public $new_chat_photo;
    /**
     * @var
     */
    public $delete_chat_photo;
    /**
     * @var
     */
    public $group_chat_created;
    /**
     * @var
     */
    public $supergroup_chat_created;
    /**
     * @var
     */
    public $channel_chat_created;
    /**
     * @var
     */
    public $migrate_to_chat_id;
    /**
     * @var
     */
    public $migrate_from_chat_id;
    /**
     * @var
     */
    public $pinned_message;
    /**
     * @var
     */
    public $invoice;
    /**
     * @var
     */
    public $successful_payment;
    /**
     * @var
     */
    public $connected_website;
    /**
     * @var
     */
    public $passport_data;

    /**
     * @param null|\stdClass $message
     * @return null|Message
     */
    public static function parseMessage(?\stdClass $message): ?self
    {

        if (is_null($message)) {
            return null;
        }
        return (new self())
            ->setMessageId($message->message_id ?? null)
            ->setFrom(User::parseUser($message->from ?? null))
            ->setDate($message->date ?? null)
            ->setChat(Chat::parseChat($message->chat ?? null))
            ->setForwardFrom(User::parseUser($message->forward_from ?? null))
            ->setForwardFromChat(Chat::parseChat($message->forward_from_chat ?? null))
            ->setForwardFromMessageId($message->forward_from_message_id ?? null)
            ->setForwardSignature($message->forward_signature ?? null)
            ->setForwardDate($message->forward_date ?? null)
            ->setReplyToMessage(Message::parseMessage($message->reply_to_message ?? null))
            ->setEditDate($message->edit_date ?? null)
            ->setMediaGroupId($message->media_group_id ?? null)
            ->setAuthorSignature($message->author_signature ?? null)
            ->setText($message->text ?? null)
            ->setEntities(MessageEntity::parseMessageEntities($message->entities ?? null))
            ->setCaptionEntities(MessageEntity::parseMessageEntities($message->caption_entities ?? null))
            ->setAudio(Audio::parseAudio($message->audio ?? null))
            ->setDocument(Document::parseDocument($message->document ?? null))
            ->setAnimation(Animation::parseAnimation($message->animation ?? null))
            ->setGame(Game::parseGame($message->game ?? null))
            ->setPhoto(PhotoSize::parsePhotoSizes($message->photo ?? null))
            ->setSticker(Sticker::parseSticker($message->sticker ?? null))
            ->setVideo(Video::parseVideo($message->video ?? null))
            ->setVoice(Voice::parseVoice($message->voice ?? null))
            ->setVideoNote(VideoNote::parseVideoNote($message->video_note ?? null))
            ->setCaption($message->caption ?? null)
            ->setContact(Contact::parseContact($message->contact ?? null))
            ->setLocation(Location::parseLocation($message->location ?? null))
            ->setVenue(Venue::parseVenue($message->venue ?? null))
            ->setNewChatMembers(User::parseUsers($message->new_chat_members ?? null))
            ->setLeftChatMember(User::parseUser($message->left_chat_member ?? null))
            ->setNewChatTitle($message->new_chat_title ?? null)
            ->setNewChatPhoto(PhotoSize::parsePhotoSizes($message->new_chat_photo ?? null))
            ->setDeleteChatPhoto($message->delete_chat_photo ?? null)
            ->setGroupChatCreated($message->group_chat_created ?? null)
            ->setSupergroupChatCreated($message->supergroup_chat_created ?? null)
            ->setChannelChatCreated($message->channel_chat_created ?? null)
            ->setMigrateToChatId($message->migrate_to_chat_id ?? null)
            ->setMigrateFromChatId($message->migrate_from_chat_id ?? null)
            ->setPinnedMessage(Message::parseMessage($message->pinned_message ?? null))
            ->setInvoice(Invoice::parseInvoice($message->invoice ?? null))
            ->setSuccessfulPayment(SuccessfulPayment::parseSuccessfulPayment($message->successful_payment ?? null))
            ->setConnectedWebsite($message->connected_website ?? null)
            ->setPassportData(PassportData::parsePassportData($message->passport_data ?? null));
    }

    /**
     * @param null|PassportData $passport_data
     * @return Message
     */
    public function setPassportData(?PassportData $passport_data): self
    {
        $this->passport_data = $passport_data;
        return $this;
    }

    /**
     * @param null|string $connected_website
     * @return Message
     */
    public function setConnectedWebsite(?string $connected_website): self
    {
        $this->connected_website = $connected_website;
        return $this;
    }

    /**
     * @param null|SuccessfulPayment $successful_payment
     * @return Message
     */
    public function setSuccessfulPayment(?SuccessfulPayment $successful_payment): self
    {
        $this->successful_payment = $successful_payment;
        return $this;
    }

    /**
     * @param null|Invoice $invoice
     * @return Message
     */
    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * @param null|Message $pinned_message
     * @return Message
     */
    public function setPinnedMessage(?Message $pinned_message): self
    {
        $this->pinned_message = $pinned_message;
        return $this;
    }

    /**
     * @param int|null $migrate_from_chat_id
     * @return Message
     */
    public function setMigrateFromChatId(?int $migrate_from_chat_id): self
    {
        $this->migrate_from_chat_id = $migrate_from_chat_id;
        return $this;
    }

    /**
     * @param int|null $migrate_to_chat_id
     * @return Message
     */
    public function setMigrateToChatId(?int $migrate_to_chat_id): self
    {
        $this->migrate_to_chat_id = $migrate_to_chat_id;
        return $this;
    }

    /**
     * @param bool|null $channel_chat_created
     * @return Message
     */
    public function setChannelChatCreated(?bool $channel_chat_created): self
    {
        $this->channel_chat_created = $channel_chat_created;
        return $this;
    }

    /**
     * @param bool|null $supergroup_chat_created
     * @return Message
     */
    public function setSupergroupChatCreated(?bool $supergroup_chat_created): self
    {
        $this->supergroup_chat_created = $supergroup_chat_created;
        return $this;
    }

    /**
     * @param bool|null $group_chat_created
     * @return Message
     */
    public function setGroupChatCreated(?bool $group_chat_created): self
    {
        $this->group_chat_created = $group_chat_created;
        return $this;
    }

    /**
     * @param bool|null $delete_chat_photo
     * @return Message
     */
    public function setDeleteChatPhoto(?bool $delete_chat_photo): self
    {
        $this->delete_chat_photo = $delete_chat_photo;
        return $this;
    }

    /**
     * @param array|null $new_chat_photo
     * @return Message
     */
    public function setNewChatPhoto(?array $new_chat_photo): self
    {
        $this->new_chat_photo = $new_chat_photo;
        return $this;
    }

    /**
     * @param bool|null $new_chat_title
     * @return Message
     */
    public function setNewChatTitle(?bool $new_chat_title): self
    {
        $this->new_chat_title = $new_chat_title;
        return $this;
    }

    /**
     * @param null|User $left_chat_member
     * @return Message
     */
    public function setLeftChatMember(?User $left_chat_member): self
    {
        $this->left_chat_member = $left_chat_member;
        return $this;
    }

    /**
     * @param array|null $new_chat_members
     * @return Message
     */
    public function setNewChatMembers(?array $new_chat_members): self
    {
        $this->new_chat_members = $new_chat_members;
        return $this;
    }

    /**
     * @param null|Venue $venue
     * @return Message
     */
    public function setVenue(?Venue $venue): self
    {
        $this->venue = $venue;
        return $this;
    }

    /**
     * @param null|Location $location
     * @return Message
     */
    public function setLocation(?Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @param null|Contact $contact
     * @return Message
     */
    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return Message
     */
    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|VideoNote $video_note
     * @return Message
     */
    public function setVideoNote(?VideoNote $video_note): self
    {
        $this->video_note = $video_note;
        return $this;
    }

    /**
     * @param null|Voice $voice
     * @return Message
     */
    public function setVoice(?Voice $voice): self
    {
        $this->voice = $voice;
        return $this;
    }

    /**
     * @param null|Video $video
     * @return Message
     */
    public function setVideo(?Video $video): self
    {
        $this->video = $video;
        return $this;
    }

    /**
     * @param null|Sticker $sticker
     * @return Message
     */
    public function setSticker(?Sticker $sticker): self
    {
        $this->sticker = $sticker;
        return $this;
    }

    /**
     * @param array|null $photo
     * @return Message
     */
    public function setPhoto(?array $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @param null|Game $game
     * @return Message
     */
    public function setGame(?Game $game): self
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @param null|Animation $animation
     * @return Message
     */
    public function setAnimation(?Animation $animation): self
    {
        $this->animation = $animation;
        return $this;
    }

    /**
     * @param null|Document $document
     * @return Message
     */
    public function setDocument(?Document $document): self
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @param null|Audio $audio
     * @return Message
     */
    public function setAudio(?Audio $audio): self
    {
        $this->audio = $audio;
        return $this;
    }

    /**
     * @param array|null $caption_entities
     * @return Message
     */
    public function setCaptionEntities(?array $caption_entities): self
    {
        $this->caption_entities = $caption_entities;
        return $this;
    }

    /**
     * @param array|null $entities
     * @return Message
     */
    public function setEntities(?array $entities): self
    {
        $this->entities = $entities;
        return $this;
    }

    /**
     * @param null|string $text
     * @return Message
     */
    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param null|string $author_signature
     * @return Message
     */
    public function setAuthorSignature(?string $author_signature): self
    {
        $this->author_signature = $author_signature;
        return $this;
    }

    /**
     * @param null|string $media_group_id
     * @return Message
     */
    public function setMediaGroupId(?string $media_group_id): self
    {
        $this->media_group_id = $media_group_id;
        return $this;
    }

    /**
     * @param int|null $edit_date
     * @return Message
     */
    public function setEditDate(?int $edit_date): self
    {
        $this->edit_date = $edit_date;
        return $this;
    }

    /**
     * @param null|Message $reply_to_message
     * @return Message
     */
    public function setReplyToMessage(?Message $reply_to_message): self
    {
        $this->reply_to_message = $reply_to_message;
        return $this;
    }

    /**
     * @param int|null $forward_date
     * @return Message
     */
    public function setForwardDate(?int $forward_date): self
    {
        $this->forward_date = $forward_date;
        return $this;
    }

    /**
     * @param null|string $forward_signature
     * @return Message
     */
    public function setForwardSignature(?string $forward_signature): self
    {
        $this->forward_signature = $forward_signature;
        return $this;
    }

    /**
     * @param int|null $forward_from_message_id
     * @return Message
     */
    public function setForwardFromMessageId(?int $forward_from_message_id): self
    {
        $this->forward_from_message_id = $forward_from_message_id;
        return $this;
    }

    /**
     * @param null|Chat $forward_from_chat
     * @return Message
     */
    public function setForwardFromChat(?Chat $forward_from_chat): self
    {
        $this->forward_from_chat = $forward_from_chat;
        return $this;
    }

    /**
     * @param null|User $forward_from
     * @return Message
     */
    public function setForwardFrom(?User $forward_from): self
    {
        $this->forward_from = $forward_from;
        return $this;
    }

    /**
     * @param null|Chat $chat
     * @return Message
     */
    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;
        return $this;
    }

    /**
     * @param null|string $date
     * @return Message
     */
    public function setDate(?string $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param null|User $from
     * @return Message
     */
    public function setFrom(?User $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param int|null $message_id
     * @return Message
     */
    public function setMessageId(?int $message_id): self
    {
        $this->message_id = $message_id;
        return $this;
    }

}