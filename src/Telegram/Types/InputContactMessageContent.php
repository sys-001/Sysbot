<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputContactMessageContent
 * @package TelegramBot\Telegram\Types
 */
class InputContactMessageContent extends InputMessageContent
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
     * @param null|\stdClass $input_contact_message_content
     * @return null|InputContactMessageContent
     */
    public static function parseInputMessageContent(?\stdClass $input_contact_message_content
    ): ?InputMessageContentInterface {
        if (is_null($input_contact_message_content)) {
            return null;
        }
        return (new self())
            ->setPhoneNumber($input_contact_message_content->phone_number ?? null)
            ->setFirstName($input_contact_message_content->first_name ?? null)
            ->setLastName($input_contact_message_content->last_name ?? null)
            ->setVcard($input_contact_message_content->vcard ?? null);
    }

    /**
     * @param null|string $vcard
     * @return InputContactMessageContent
     */
    public function setVcard(?string $vcard): InputMessageContentInterface
    {
        $this->vcard = $vcard;
        return $this;
    }

    /**
     * @param null|string $last_name
     * @return InputContactMessageContent
     */
    public function setLastName(?string $last_name): InputMessageContentInterface
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param null|string $first_name
     * @return InputContactMessageContent
     */
    public function setFirstName(?string $first_name): InputMessageContentInterface
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param null|string $phone_number
     * @return InputContactMessageContent
     */
    public function setPhoneNumber(?string $phone_number): InputMessageContentInterface
    {
        $this->phone_number = $phone_number;
        return $this;
    }

}