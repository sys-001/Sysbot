<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultVoice
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultVoice extends InlineQueryResult
{

    /**
     * @var
     */
    public $voice_url;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $caption;
    /**
     * @var
     */
    public $parse_mode;
    /**
     * @var
     */
    public $voice_duration;
    /**
     * @var
     */
    public $input_message_content;


    /**
     * @param null|\stdClass $inline_query_result_voice
     * @return null|InlineQueryResultVoice
     */
    public static function parseInlineQueryResultVoice(?\stdClass $inline_query_result_voice
    ): ?InlineQueryResultInterface {
        if (is_null($inline_query_result_voice)) {
            return null;
        }
        return (new self())
            ->setVoiceUrl($inline_query_result_voice->voice_url ?? null)
            ->setTitle($inline_query_result_voice->title ?? null)
            ->setCaption($inline_query_result_voice->caption ?? null)
            ->setParseMode($inline_query_result_voice->parse_mode ?? null)
            ->setVoiceDuration($inline_query_result_voice->voice_duration ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_voice->input_message_content ?? null))
            ->setType($inline_query_result_voice->type ?? null)
            ->setId($inline_query_result_voice->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_voice->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultVoice
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param int|null $voice_duration
     * @return InlineQueryResultVoice
     */
    public function setVoiceDuration(?int $voice_duration): InlineQueryResultInterface
    {
        $this->voice_duration = $voice_duration;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultVoice
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultVoice
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultVoice
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $voice_url
     * @return InlineQueryResultVoice
     */
    public function setVoiceUrl(?string $voice_url): InlineQueryResultInterface
    {
        $this->voice_url = $voice_url;
        return $this;
    }

}