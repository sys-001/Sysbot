<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultArticle
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultArticle extends InlineQueryResult
{

    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $input_message_content;
    /**
     * @var
     */
    public $url;
    /**
     * @var
     */
    public $hide_url;
    /**
     * @var
     */
    public $description;
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
     * @param null|\stdClass $inline_query_result_article
     * @return null|InlineQueryResultArticle
     */
    public static function parseInlineQueryResult(?\stdClass $inline_query_result_article): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_article)) return null;
        return (new self())
            ->setTitle($inline_query_result_article->title ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_article->input_message_content ?? null))
            ->setUrl($inline_query_result_article->url ?? null)
            ->setHideUrl($inline_query_result_article->hide_url ?? null)
            ->setDescription($inline_query_result_article->description ?? null)
            ->setThumbUrl($inline_query_result_article->thumb_url ?? null)
            ->setThumbWidth($inline_query_result_article->thumb_width ?? null)
            ->setThumbHeight($inline_query_result_article->thumb_height ?? null)
            ->setType($inline_query_result_article->type ?? null)
            ->setId($inline_query_result_article->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_article->reply_markup ?? null));
    }

    /**
     * @param int|null $thumb_height
     * @return InlineQueryResultArticle
     */
    public function setThumbHeight(?int $thumb_height): InlineQueryResultInterface
    {
        $this->thumb_height = $thumb_height;
        return $this;
    }

    /**
     * @param int|null $thumb_width
     * @return InlineQueryResultArticle
     */
    public function setThumbWidth(?int $thumb_width): InlineQueryResultInterface
    {
        $this->thumb_width = $thumb_width;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultArticle
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param null|string $description
     * @return InlineQueryResultArticle
     */
    public function setDescription(?string $description): InlineQueryResultInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param bool|null $hide_url
     * @return InlineQueryResultArticle
     */
    public function setHideUrl(?bool $hide_url): InlineQueryResultInterface
    {
        $this->hide_url = $hide_url;
        return $this;
    }

    /**
     * @param null|string $url
     * @return InlineQueryResultArticle
     */
    public function setUrl(?string $url): InlineQueryResultInterface
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultArticle
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param string|null $title
     * @return InlineQueryResultArticle
     */
    public function setTitle(?string $title): InlineQueryResultInterface
    {
        $this->title = $title;
        return $this;
    }

}