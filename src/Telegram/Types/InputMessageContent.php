<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputMessageContent
 * @package TelegramBot\Telegram\Types
 */
class InputMessageContent implements InputMessageContentInterface
{

    /**
     * @param null|\stdClass $input_message_content
     * @return null|InputMessageContent
     */
    public static function parseInputMessageContent(?\stdClass $input_message_content): ?InputMessageContentInterface
    {
        if (empty($input_message_content->type)) {
            return null;
        }
        $class_name = sprintf('InputMessageContent%s', ucfirst($input_message_content->type));
        return call_user_func([$class_name, 'parseInputMessageContent'], $input_message_content);
    }

}