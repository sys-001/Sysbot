<?php

namespace TelegramBot\Types\Telegram;

use TelegramBot\Types\Exception\TelegramBotException;

/**
 * Class Response
 * @package TelegramBot\Types\Telegram
 */
class Response
{
    /**
     * @var bool
     */
    public $ok;
    /**
     * @var \stdClass
     */
    public $result;
    /**
     * @var int
     */
    public $error_code;
    /**
     * @var string
     */
    public $description;

    /**
     * Response constructor.
     * @param bool $ok
     * @param \stdClass $result
     * @param int $error_code
     * @param string $description
     * @throws TelegramBotException
     */
    public function __construct(bool $ok, $result = null, int $error_code = null, string $description = null)
    {
        if (is_array($result)) $result = (object)$result;
        if ($ok) {
            if (empty($result)) throw new TelegramBotException("Invalid Response parameters provided.");
            $this->result = $result;
            $this->error_code = null;
            $this->description = null;
        } else {
            if (empty($error_code) or empty($description)) throw new TelegramBotException("Invalid Response parameters provided.");
            $this->result = null;
            $this->error_code = $error_code;
            $this->description = $description;
        }
        $this->ok = $ok;
    }


}