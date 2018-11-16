<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResult
 * @package TelegramBot\Telegram\Types
 */
abstract class InlineQueryResult implements InlineQueryResultInterface
{

    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $reply_markup;

    /**
     * @param null|\stdClass $inline_query_result
     * @return null|InlineQueryResult
     */
    public static function parseInlineQueryResult(?\stdClass $inline_query_result): ?InlineQueryResultInterface
    {
        if (empty($inline_query_result->type)) {
            return null;
        }
        $class_name = sprintf('InlineQueryResult%s', ucfirst($inline_query_result->type));
        return call_user_func([$class_name, 'parseInlineQueryResult'], $inline_query_result);
    }


    /**
     * @param null|string $type
     * @return InlineQueryResult
     */
    public function setType(?string $type): InlineQueryResultInterface
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @param null|string $id
     * @return InlineQueryResult
     */
    public function setId(?string $id): InlineQueryResultInterface
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @param null|InlineKeyboardMarkup $reply_markup
     * @return InlineQueryResult
     */
    public function setReplyMarkup(?InlineKeyboardMarkup $reply_markup): InlineQueryResultInterface
    {
        $this->reply_markup = $reply_markup;
        return $this;
    }


}