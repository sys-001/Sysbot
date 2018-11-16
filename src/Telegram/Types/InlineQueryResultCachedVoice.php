<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedVoice
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedVoice extends InlineQueryResult
{

    /**
     * @var
     */
    public $voice_file_id;
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
    public $input_message_content;


    /**
     * @param null|\stdClass $inline_query_result_cached_voice
     * @return null|InlineQueryResultCachedVoice
     */
    public static function parseInlineQueryResultCachedVoice(?\stdClass $inline_query_result_cached_voice): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_cached_voice)) return null;
        return (new self())
            ->setVoiceFileId($inline_query_result_cached_voice->voice_file_id ?? null)
            ->setTitle($inline_query_result_cached_voice->title ?? null)
            ->setCaption($inline_query_result_cached_voice->caption ?? null)
            ->setParseMode($inline_query_result_cached_voice->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_voice->input_message_content ?? null))
            ->setType($inline_query_result_cached_voice->type ?? null)
            ->setId($inline_query_result_cached_voice->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_voice->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedVoice
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultCachedVoice
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultCachedVoice
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultCachedVoice
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $voice_file_id
     * @return InlineQueryResultCachedVoice
     */
    public function setVoiceFileId(?string $voice_file_id): InlineQueryResultInterface
    {
        $this->voice_file_id = $voice_file_id;
        return $this;
    }

}