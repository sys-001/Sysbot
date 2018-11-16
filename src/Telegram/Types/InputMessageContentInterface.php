<?php

namespace TelegramBot\Telegram\Types;


/**
 * Interface InputMessageContentInterface
 * @package TelegramBot\Telegram\Types
 */
interface InputMessageContentInterface
{
    /**
     * @param null|\stdClass $input_message_content
     * @return null|InputMessageContentInterface
     */
    public static function parseInputMessageContent(?\stdClass $input_message_content): ?self;
}