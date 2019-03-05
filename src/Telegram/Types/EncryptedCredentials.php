<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class EncryptedCredentials
 * @package TelegramBot\Telegram\Types
 */
class EncryptedCredentials
{

    /**
     * @var
     */
    public $data;
    /**
     * @var
     */
    public $hash;
    /**
     * @var
     */
    public $secret;


    /**
     * @param null|\stdClass $encrypted_credentials
     * @return null|EncryptedCredentials
     */
    public static function parseEncryptedCredentials(?\stdClass $encrypted_credentials): ?self
    {
        if (is_null($encrypted_credentials)) return null;
        return (new self())
            ->setData($encrypted_credentials->data ?? null)
            ->setHash($encrypted_credentials->hash ?? null)
            ->setSecret($encrypted_credentials->secret ?? null);
    }

    /**
     * @param null|string $secret
     * @return EncryptedCredentials
     */
    public function setSecret(?string $secret): self
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @param null|string $hash
     * @return EncryptedCredentials
     */
    public function setHash(?string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @param null|string $data
     * @return EncryptedCredentials
     */
    public function setData(?string $data): self
    {
        $this->data = $data;
        return $this;
    }

}