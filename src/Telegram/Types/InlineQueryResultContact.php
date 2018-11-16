<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InlineQueryResultContact
 * @package TelegramBot\Telegram\Types
 */
class InlineQueryResultContact extends InlineQueryResult
{

    /**
     * @var
     */
    public $phone_number;
    /**
     * @var
     */
    public $first_name;
    /**
     * @var
     */
    public $last_name;
    /**
     * @var
     */
    public $vcard;
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
     * @param null|\stdClass $inline_query_result_contact
     * @return null|InlineQueryResultContact
     */
    public static function parseInlineQueryResultContact(?\stdClass $inline_query_result_contact): ?InlineQueryResultInterface
    {
        if (is_null($inline_query_result_contact)) return null;
        return (new self())
            ->setPhoneNumber($inline_query_result_contact->phone_number ?? null)
            ->setFirstName($inline_query_result_contact->first_name ?? null)
            ->setLastName($inline_query_result_contact->last_name ?? null)
            ->setVcard($inline_query_result_contact->vcard ?? null)
            ->setInputMessageContent(InputMessageContent::parseInputMessageContent($inline_query_result_contact->input_message_content ?? null))
            ->setThumbUrl($inline_query_result_contact->thumb_url ?? null)
            ->setThumbWidth($inline_query_result_contact->thumb_width ?? null)
            ->setThumbHeight($inline_query_result_contact->thumb_height ?? null)
            ->setType($inline_query_result_contact->type ?? null)
            ->setId($inline_query_result_contact->id ?? null)
            ->setReplyMarkup(InlineKeyboardMarkup::parseInlineKeyboardMarkup($inline_query_result_contact->reply_markup ?? null));
    }

    /**
     * @param int|null $thumb_height
     * @return InlineQueryResultContact
     */
    public function setThumbHeight(?int $thumb_height): InlineQueryResultInterface
    {
        $this->thumb_height = $thumb_height;
        return $this;
    }

    /**
     * @param int|null $thumb_width
     * @return InlineQueryResultContact
     */
    public function setThumbWidth(?int $thumb_width): InlineQueryResultInterface
    {
        $this->thumb_width = $thumb_width;
        return $this;
    }

    /**
     * @param null|string $thumb_url
     * @return InlineQueryResultContact
     */
    public function setThumbUrl(?string $thumb_url): InlineQueryResultInterface
    {
        $this->thumb_url = $thumb_url;
        return $this;
    }

    /**
     * @param null|InputMessageContent $input_message_content
     * @return InlineQueryResultContact
     */
    public function setInputMessageContent(?InputMessageContent $input_message_content): InlineQueryResultInterface
    {
        $this->input_message_content = $input_message_content;
        return $this;
    }

    /**
     * @param null|string $vcard
     * @return InlineQueryResultContact
     */
    public function setVcard(?string $vcard): InlineQueryResultInterface
    {
        $this->vcard = $vcard;
        return $this;
    }

    /**
     * @param null|string $last_name
     * @return InlineQueryResultContact
     */
    public function setLastName(?string $last_name): InlineQueryResultInterface
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param null|string $first_name
     * @return InlineQueryResultContact
     */
    public function setFirstName(?string $first_name): InlineQueryResultInterface
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param null|string $phone_number
     * @return InlineQueryResultContact
     */
    public function setPhoneNumber(?string $phone_number): InlineQueryResultInterface
    {
        $this->phone_number = $phone_number;
        return $this;
    }

}