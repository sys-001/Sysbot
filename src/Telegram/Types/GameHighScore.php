<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class GameHighScore
 * @package TelegramBot\Telegram\Types
 */
class GameHighScore
{

    /**
     * @var
     */
    public $position;
    /**
     * @var
     */
    public $user;
    /**
     * @var
     */
    public $score;


    /**
     * @param null|\stdClass $game_high_score
     * @return null|GameHighScore
     */
    public static function parseGameHighScore(?\stdClass $game_high_score): ?self
    {
        if (is_null($game_high_score)) {
            return null;
        }
        return (new self())
            ->setPosition($game_high_score->position ?? null)
            ->setUser(User::parseUser($game_high_score->user ?? null))
            ->setScore($game_high_score->score ?? null);
    }

    /**
     * @param int|null $score
     * @return GameHighScore
     */
    public function setScore(?int $score): self
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @param null|User $user
     * @return GameHighScore
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param int|null $position
     * @return GameHighScore
     */
    public function setPosition(?int $position): self
    {
        $this->position = $position;
        return $this;
    }

}