<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ChosenInlineResult
 * @package TelegramBot\Telegram\Types
 */
class ChosenInlineResult
{

    /**
     * @var
     */
    public $result_id;
    /**
     * @var
     */
    public $from;
    /**
     * @var
     */
    public $location;
    /**
     * @var
     */
    public $inline_message_id;
    /**
     * @var
     */
    public $query;


    /**
     * @param null|\stdClass $chosen_inline_result
     * @return null|ChosenInlineResult
     */
    public static function parseChosenInlineResult(?\stdClass $chosen_inline_result): ?self
    {
        if (is_null($chosen_inline_result)) return null;
        return (new self())
            ->setResultId($chosen_inline_result->result_id ?? null)
            ->setFrom(User::parseUser($chosen_inline_result->from ?? null))
            ->setLocation(Location::parseLocation($chosen_inline_result->location ?? null))
            ->setInlineMessageId($chosen_inline_result->inline_message_id ?? null)
            ->setQuery($chosen_inline_result->query ?? null);
    }

    /**
     * @param null|string $query
     * @return ChosenInlineResult
     */
    public function setQuery(?string $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param null|string $inline_message_id
     * @return ChosenInlineResult
     */
    public function setInlineMessageId(?string $inline_message_id): self
    {
        $this->inline_message_id = $inline_message_id;
        return $this;
    }

    /**
     * @param null|Location $location
     * @return ChosenInlineResult
     */
    public function setLocation(?Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @param null|User $from
     * @return ChosenInlineResult
     */
    public function setFrom(?User $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param null|string $result_id
     * @return ChosenInlineResult
     */
    public function setResultId(?string $result_id): self
    {
        $this->result_id = $result_id;
        return $this;
    }

}