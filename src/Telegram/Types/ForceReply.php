<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ForceReply
 * @package TelegramBot\Telegram\Types
 */
class ForceReply implements ReplyMarkupInterface
{

    /**
     * @var
     */
    public $force_reply;
    /**
     * @var
     */
    public $selective;


    /**
     * @param null|\stdClass $force_reply
     * @return null|ForceReply
     */
    public static function parseForceReply(?\stdClass $force_reply): ?self
    {
        if (is_null($force_reply)) return null;
        return (new self())
            ->setForceReply($force_reply->force_reply ?? null)
            ->setSelective($force_reply->selective ?? null);
    }

    /**
     * @param bool|null $selective
     * @return ForceReply
     */
    public function setSelective(?bool $selective): self
    {
        $this->selective = $selective;
        return $this;
    }

    /**
     * @param bool|null $force_reply
     * @return ForceReply
     */
    public function setForceReply(?bool $force_reply): self
    {
        $this->force_reply = $force_reply;
        return $this;
    }

}