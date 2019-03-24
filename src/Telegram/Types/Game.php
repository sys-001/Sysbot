<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Game
 * @package TelegramBot\Telegram\Types
 */
class Game
{

    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $description;
    /**
     * @var
     */
    public $photo;
    /**
     * @var
     */
    public $text;
    /**
     * @var
     */
    public $text_entities;
    /**
     * @var
     */
    public $animation;


    /**
     * @param null|\stdClass $game
     * @return null|Game
     */
    public static function parseGame(?\stdClass $game): ?self
    {
        if (is_null($game)) {
            return null;
        }
        return (new self())
            ->setTitle($game->title ?? null)
            ->setDescription($game->description ?? null)
            ->setPhoto(PhotoSize::parsePhotoSizes($game->photo ?? null))
            ->setText($game->text ?? null)
            ->setTextEntities(MessageEntity::parseMessageEntities($game->text_entities ?? null))
            ->setAnimation(Animation::parseAnimation($game->animation ?? null));
    }

    /**
     * @param null|Animation $animation
     * @return Game
     */
    public function setAnimation(?Animation $animation): self
    {
        $this->animation = $animation;
        return $this;
    }

    /**
     * @param array|null $text_entities
     * @return Game
     */
    public function setTextEntities(?array $text_entities): self
    {
        $this->text_entities = $text_entities;
        return $this;
    }

    /**
     * @param null|string $text
     * @return Game
     */
    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param array|null $photo
     * @return Game
     */
    public function setPhoto(?array $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @param null|string $description
     * @return Game
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param null|string $title
     * @return Game
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

}