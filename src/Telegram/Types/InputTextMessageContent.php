<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputTextMessageContent
 * @package TelegramBot\Telegram\Types
 */
class InputTextMessageContent extends InputMessageContent
{

    /**
     * @var
     */
    public $message_text;
    /**
     * @var
     */
    public $parse_mode;
    /**
     * @var
     */
    public $disable_web_page_preview;


    /**
     * @param null|\stdClass $input_text_message_content
     * @return null|InputTextMessageContent
     */
    public static function parseInputMessageContent(?\stdClass $input_text_message_content): ?InputMessageContentInterface
    {
        if (is_null($input_text_message_content)) return null;
        return (new self())
            ->setMessageText($input_text_message_content->message_text ?? null)
            ->setParseMode($input_text_message_content->parse_mode ?? null)
            ->setDisableWebPagePreview($input_text_message_content->disable_web_page_preview ?? null);
    }

    /**
     * @param bool|null $disable_web_page_preview
     * @return InputTextMessageContent
     */
    public function setDisableWebPagePreview(?bool $disable_web_page_preview): InputMessageContentInterface
    {
        $this->disable_web_page_preview = $disable_web_page_preview;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InputTextMessageContent
     */
    public function setParseMode(?string $parse_mode): InputMessageContentInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $message_text
     * @return InputTextMessageContent
     */
    public function setMessageText(?string $message_text): InputMessageContentInterface
    {
        $this->message_text = $message_text;
        return $this;
    }

}