<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultAudio
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultAudio extends InlineQueryResult
{

    /**
     * @var
     */
    public $audio_url;
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
    public $performer;
    /**
     * @var
     */
    public $audio_duration;
    /**
     * @var
     */
    public $input_message_content;


    /**
     * @param null|\stdClass $inline_query_result_audio
     * @return null|InlineQueryResultAudio
     */
    public static function parseInlineQueryResult(?\stdClass $inline_query_result_audio): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_audio)) return null;
        return (new self())
            ->setAudioUrl($inline_query_result_audio->audio_url ?? null)
            ->setTitle($inline_query_result_audio->title ?? null)
            ->setCaption($inline_query_result_audio->caption ?? null)
            ->setParseMode($inline_query_result_audio->parse_mode ?? null)
            ->setPerformer($inline_query_result_audio->performer ?? null)
            ->setAudioDuration($inline_query_result_audio->audio_duration ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_audio->input_message_content ?? null))
            ->setType($inline_query_result_audio->type ?? null)
            ->setId($inline_query_result_audio->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_audio->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultAudio
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param int|null $audio_duration
     * @return InlineQueryResultAudio
     */
    public function setAudioDuration(?int $audio_duration): InlineQueryResultInterface
    {
        $this->audio_duration = $audio_duration;
        return $this;
    }

    /**
     * @param null|string $performer
     * @return InlineQueryResultAudio
     */
    public function setPerformer(?string $performer): InlineQueryResultInterface
    {
        $this->performer = $performer;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultAudio
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultAudio
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param string|null $title
     * @return InlineQueryResultAudio
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null|string $audio_url
     * @return InlineQueryResultAudio
     */
    public function setAudioUrl(?string $audio_url): InlineQueryResultInterface
    {
        $this->audio_url = $audio_url;
        return $this;
    }

}