<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ChatPhoto
 * @package TelegramBot\Telegram\Types
 */
class ChatPhoto
{

    /**
     * @var
     */
    public $small_file_id;
    /**
     * @var
     */
    public $big_file_id;


    /**
     * @param null|\stdClass $chat_photo
     * @return null|ChatPhoto
     */
    public static function parseChatPhoto(?\stdClass $chat_photo): ?self
    {
        if (is_null($chat_photo)) return null;
        return (new self())
            ->setSmallFileId($chat_photo->small_file_id ?? null)
            ->setBigFileId($chat_photo->big_file_id ?? null);
    }

    /**
     * @param null|string $big_file_id
     * @return ChatPhoto
     */
    public function setBigFileId(?string $big_file_id): self
    {
        $this->big_file_id = $big_file_id;
        return $this;
    }

    /**
     * @param null|string $small_file_id
     * @return ChatPhoto
     */
    public function setSmallFileId(?string $small_file_id): self
    {
        $this->small_file_id = $small_file_id;
        return $this;
    }

}