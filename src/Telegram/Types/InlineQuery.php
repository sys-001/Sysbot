<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQuery
 * @package TelegramBot\Telegram\Types
 */
class InlineQuery
{

    /**
     * @var
     */
    public $id;
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
    public $query;
    /**
     * @var
     */
    public $offset;


    /**
     * @param null|\stdClass $inline_query
     * @return null|InlineQuery
     */
    public static function parseInlineQuery(?\stdClass $inline_query): ?self
    {
        if (is_null($inline_query)) return null;
        return (new self())
            ->setId($inline_query->id ?? null)
            ->setFrom(User::parseUser($inline_query->from ?? null))
            ->setLocation(Location::parseLocation($inline_query->location ?? null))
            ->setQuery($inline_query->query ?? null)
            ->setOffset($inline_query->offset ?? null);
    }

    /**
     * @param null|string $offset
     * @return InlineQuery
     */
    public function setOffset(?string $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param null|string $query
     * @return InlineQuery
     */
    public function setQuery(?string $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param null|Location $location
     * @return InlineQuery
     */
    public function setLocation(?Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @param null|User $from
     * @return InlineQuery
     */
    public function setFrom(?User $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param null|string $id
     * @return InlineQuery
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

}