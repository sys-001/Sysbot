<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedAudio
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedAudio extends InlineQueryResult
{

    /**
     * @var
     */
    public $audio_file_id;
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
     * @param null|\stdClass $inline_query_result_cached_audio
     * @return null|InlineQueryResultCachedAudio
     */
    public static function parseInlineQueryResultCachedAudio(?\stdClass $inline_query_result_cached_audio
    ): ?InlineQueryResultInterface {
        if (is_null($inline_query_result_cached_audio)) {
            return null;
        }
        return (new self())
            ->setAudioFileId($inline_query_result_cached_audio->audio_file_id ?? null)
            ->setCaption($inline_query_result_cached_audio->caption ?? null)
            ->setParseMode($inline_query_result_cached_audio->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_audio->input_message_content ?? null))
            ->setType($inline_query_result_cached_audio->type ?? null)
            ->setId($inline_query_result_cached_audio->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_audio->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedAudio
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultCachedAudio
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultCachedAudio
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $audio_file_id
     * @return InlineQueryResultCachedAudio
     */
    public function setAudioFileId(?string $audio_file_id): InlineQueryResultInterface
    {
        $this->audio_file_id = $audio_file_id;
        return $this;
    }

}