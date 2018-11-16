<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultDocument
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultDocument extends InlineQueryResult
{

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
    public $document_url;
    /**
     * @var
     */
    public $mime_type;
    /**
     * @var
     */
    public $description;
    /**
     * @var
     */
    public $input_message_content;
    /**
     * @var
     */
    public $thumb_url;
    /**
     * @var
     */
    public $thumb_width;
    /**
     * @var
     */
    public $thumb_height;


    /**
     * @param null|\stdClass $inline_query_result_document
     * @return null|InlineQueryResultDocument
     */
    public static function parseInlineQueryResultDocument(?\stdClass $inline_query_result_document): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_document)) return null;
        return (new self())
            ->setTitle($inline_query_result_document->title ?? null)
            ->setCaption($inline_query_result_document->caption ?? null)
            ->setParseMode($inline_query_result_document->parse_mode ?? null)
            ->setDocumentUrl($inline_query_result_document->document_url ?? null)
            ->setMimeType($inline_query_result_document->mime_type ?? null)
            ->setDescription($inline_query_result_document->description ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_document->input_message_content ?? null))
            ->setThumbUrl($inline_query_result_document->thumb_url ?? null)
            ->setThumbWidth($inline_query_result_document->thumb_width ?? null)
            ->setThumbHeight($inline_query_result_document->thumb_height ?? null)
            ->setType($inline_query_result_document->type ?? null)
            ->setId($inline_query_result_document->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_document->reply_markup ?? null));
    }

    /**
     * @param int|null $thumb_height
     * @return InlineQueryResultDocument
     */
    public function setThumbHeight(?int $thumb_height): InlineQueryResultInterface
    {
        $this->thumb_height = $thumb_height;
        return $this;
    }

    /**
     * @param int|null $thumb_width
     * @return InlineQueryResultDocument
     */
    public function setThumbWidth(?int $thumb_width): InlineQueryResultInterface
    {
        $this->thumb_width = $thumb_width;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultDocument
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultDocument
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $description
     * @return InlineQueryResultDocument
     */
    public function setDescription(?string $description): InlineQueryResultInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param null|string $mime_type
     * @return InlineQueryResultDocument
     */
    public function setMimeType(?string $mime_type): InlineQueryResultInterface
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @param null|string $document_url
     * @return InlineQueryResultDocument
     */
    public function setDocumentUrl(?string $document_url): InlineQueryResultInterface
    {
        $this->document_url = $document_url;
        return $this;
    }

    /**
     * @param null|string $parse_mode
     * @return InlineQueryResultDocument
     */
    public function setParseMode(?string $parse_mode): InlineQueryResultInterface
    {
        $this->parse_mode = $parse_mode;
        return $this;
    }

    /**
     * @param null|string $caption
     * @return InlineQueryResultDocument
     */
    public function setCaption(?string $caption): InlineQueryResultInterface
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param null|string $title
     * @return InlineQueryResultDocument
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

}