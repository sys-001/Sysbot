<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class PassportData
 * @package TelegramBot\Telegram\Types
 */
class PassportData
{

    /**
     * @var
     */
    public $data;
    /**
     * @var
     */
    public $credentials;


    /**
     * @param null|\stdClass $passport_data
     * @return null|PassportData
     */
    public static function parsePassportData(?\stdClass $passport_data): ?self
    {
        if (is_null($passport_data)) {
            return null;
        }
        return (new self())
            ->setData(EncryptedPassportElement::parseEncryptedPassportElements($passport_data->data ?? null))
            ->setCredentials(EncryptedCredentials::parseEncryptedCredentials($passport_data->credentials ?? null));
    }

    /**
     * @param null|EncryptedCredentials $credentials
     * @return PassportData
     */
    public function setCredentials(?EncryptedCredentials $credentials): self
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * @param array|null $data
     * @return PassportData
     */
    public function setData(?array $data): self
    {
        $this->data = $data;
        return $this;
    }

}