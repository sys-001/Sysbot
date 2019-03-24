<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class ResponseParameters
 * @package TelegramBot\Telegram\Types
 */
class ResponseParameters
{

    /**
     * @var
     */
    public $migrate_to_chat_id;
    /**
     * @var
     */
    public $retry_after;


    /**
     * @param null|\stdClass $response_parameters
     * @return null|ResponseParameters
     */
    public static function parseResponseParameters(?\stdClass $response_parameters): ?self
    {
        if (is_null($response_parameters)) {
            return null;
        }
        return (new self())
            ->setMigrateToChatId($response_parameters->migrate_to_chat_id ?? null)
            ->setRetryAfter($response_parameters->retry_after ?? null);
    }

    /**
     * @param int|null $retry_after
     * @return ResponseParameters
     */
    public function setRetryAfter(?int $retry_after): self
    {
        $this->retry_after = $retry_after;
        return $this;
    }

    /**
     * @param int|null $migrate_to_chat_id
     * @return ResponseParameters
     */
    public function setMigrateToChatId(?int $migrate_to_chat_id): self
    {
        $this->migrate_to_chat_id = $migrate_to_chat_id;
        return $this;
    }

}