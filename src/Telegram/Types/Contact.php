<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class Contact
 * @package TelegramBot\Telegram\Types
 */
class Contact
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
    public $user_id;
    /**
     * @var
     */
    public $vcard;


    /**
     * @param null|\stdClass $contact
     * @return null|Contact
     */
    public static function parseContact(?\stdClass $contact): ?self
    {
        if (is_null($contact)) return null;
        return (new self())
            ->setPhoneNumber($contact->phone_number ?? null)
            ->setFirstName($contact->first_name ?? null)
            ->setLastName($contact->last_name ?? null)
            ->setUserId($contact->user_id ?? null)
            ->setVcard($contact->vcard ?? null);
    }

    /**
     * @param null|string $vcard
     * @return Contact
     */
    public function setVcard(?string $vcard): self
    {
        $this->vcard = $vcard;
        return $this;
    }

    /**
     * @param int|null $user_id
     * @return Contact
     */
    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @param null|string $last_name
     * @return Contact
     */
    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param null|string $first_name
     * @return Contact
     */
    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param null|string $phone_number
     * @return Contact
     */
    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;
        return $this;
    }

}