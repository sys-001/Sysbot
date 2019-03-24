<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultCachedDocument
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultCachedDocument extends InlineQueryResult
{

    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $document_file_id;
    /**
     * @var
     */
    public $description;
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
     * @param null|\stdClass $inline_query_result_cached_document
     * @return null|InlineQueryResultCachedDocument
     */
    public static function parseInlineQueryResultCachedDocument(?\stdClass $inline_query_result_cached_document
    ): ?InlineQueryResultInterface {
        if (is_null($inline_query_result_cached_document)) {
            return null;
        }
        return (new self())
            ->setTitle($inline_query_result_cached_document->title ?? null)
            ->setDocumentFileId($inline_query_result_cached_document->document_file_id ?? null)
            ->setDescription($inline_query_result_cached_document->description ?? null)
            ->setCaption($inline_query_result_cached_document->caption ?? null)
            ->setParseMode($inline_query_result_cached_document->parse_mode ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_cached_document->input_message_content ?? null))
            ->setType($inline_query_result_cached_document->type ?? null)
            ->setId($inline_query_result_cached_document->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_cached_document->reply_markup ?? null));
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultCachedDocument
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultCachedDocument
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultCachedDocument
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $description
     * @return InlineQueryResultCachedDocument
     */
    public function setDescription(?string $description): InlineQueryResultInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param null|string $document_file_id
     * @return InlineQueryResultCachedDocument
     */
    public function setDocumentFileId(?string $document_file_id): InlineQueryResultInterface
    {
        $this->document_file_id = $document_file_id;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultCachedDocument
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

}