<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class EncryptedPassportElement
 * @package TelegramBot\Telegram\Types
 */
class EncryptedPassportElement
{

    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $data;
    /**
     * @var
     */
    public $phone_number;
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $files;
    /**
     * @var
     */
    public $front_side;
    /**
     * @var
     */
    public $reverse_side;
    /**
     * @var
     */
    public $selfie;
    /**
     * @var
     */
    public $translation;
    /**
     * @var
     */
    public $hash;


    /**
     * @param null|\stdClass $encrypted_passport_element
     * @return null|EncryptedPassportElement
     */
    public static function parseEncryptedPassportElement(?\stdClass $encrypted_passport_element): ?self
    {
        if (is_null($encrypted_passport_element)) {
            return null;
        }
        return (new self())
            ->setType($encrypted_passport_element->type ?? null)
            ->setData($encrypted_passport_element->data ?? null)
            ->setPhoneNumber($encrypted_passport_element->phone_number ?? null)
            ->setEmail($encrypted_passport_element->email ?? null)
            ->setFiles(PassportFile::parsePassportFiles($encrypted_passport_element->files ?? null))
            ->setFrontSide(PassportFile::parsePassportFile($encrypted_passport_element->front_side ?? null))
            ->setReverseSide(PassportFile::parsePassportFile($encrypted_passport_element->reverse_side ?? null))
            ->setSelfie(PassportFile::parsePassportFile($encrypted_passport_element->selfie ?? null))
            ->setTranslation(PassportFile::parsePassportFiles($encrypted_passport_element->translation ?? null))
            ->setHash($encrypted_passport_element->hash ?? null);
    }

    /**
     * @param null|string $hash
     * @return EncryptedPassportElement
     */
    public function setHash(?string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @param array|null $translation
     * @return EncryptedPassportElement
     */
    public function setTranslation(?array $translation): self
    {
        $this->translation = $translation;
        return $this;
    }

    /**
     * @param null|PassportFile $selfie
     * @return EncryptedPassportElement
     */
    public function setSelfie(?PassportFile $selfie): self
    {
        $this->selfie = $selfie;
        return $this;
    }

    /**
     * @param null|PassportFile $reverse_side
     * @return EncryptedPassportElement
     */
    public function setReverseSide(?PassportFile $reverse_side): self
    {
        $this->reverse_side = $reverse_side;
        return $this;
    }

    /**
     * @param null|PassportFile $front_side
     * @return EncryptedPassportElement
     */
    public function setFrontSide(?PassportFile $front_side): self
    {
        $this->front_side = $front_side;
        return $this;
    }

    /**
     * @param array|null $files
     * @return EncryptedPassportElement
     */
    public function setFiles(?array $files): self
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @param null|string $email
     * @return EncryptedPassportElement
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param null|string $phone_number
     * @return EncryptedPassportElement
     */
    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;
        return $this;
    }

    /**
     * @param null|string $data
     * @return EncryptedPassportElement
     */
    public function setData(?string $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param null|string $type
     * @return EncryptedPassportElement
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param array|null $encrypted_passport_elements
     * @return array|null
     */
    public static function parseEncryptedPassportElements(?array $encrypted_passport_elements): ?array
    {
        if (is_null($encrypted_passport_elements)) {
            return null;
        }
        return array_map(['self', 'parseEncryptedPassportElement'], $encrypted_passport_elements);
    }
}