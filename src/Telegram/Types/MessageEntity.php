<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class MessageEntity
 * @package TelegramBot\Telegram\Types
 */
class MessageEntity
{

    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $offset;
    /**
     * @var
     */
    public $length;
    /**
     * @var
     */
    public $url;
    /**
     * @var
     */
    public $user;


    /**
     * @param null|\stdClass $message_entity
     * @return null|MessageEntity
     */
    public static function parseMessageEntity(?\stdClass $message_entity): ?self
    {
        if (is_null($message_entity)) return null;
        return (new self())
            ->setType($message_entity->type ?? null)
            ->setOffset($message_entity->offset ?? null)
            ->setLength($message_entity->length ?? null)
            ->setUrl($message_entity->url ?? null)
            ->setUser(User::parseUser($message_entity->user ?? null));
    }

    /**
     * @param mixed $user
     * @return MessageEntity
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param mixed $url
     * @return MessageEntity
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param mixed $length
     * @return MessageEntity
     */
    public function setLength(?int $length): self
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @param mixed $offset
     * @return MessageEntity
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param mixed $type
     * @return MessageEntity
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param array|null $message_entities
     * @return array|null
     */
    public static function parseMessageEntities(?array $message_entities): ?array
    {
        if (is_null($message_entities)) return null;
        return array_map(['self', 'parseMessageEntity'], $message_entities);
    }
}