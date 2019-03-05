<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class User
 * @package TelegramBot\Telegram\Types
 */
class User
{

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $is_bot;
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
    public $username;
    /**
     * @var
     */
    public $language_code;

    /**
     * @param null|\stdClass $user
     * @return null|User
     */
    public static function parseUser(?\stdClass $user): ?self
    {
        if (is_null($user)) return null;
        return (new self())
            ->setId($user->id ?? null)
            ->setIsBot($user->is_bot ?? null)
            ->setFirstName($user->first_name ?? null)
            ->setLastName($user->last_name ?? null)
            ->setUsername($user->username ?? null)
            ->setLanguageCode($user->language_code ?? null);
    }

    /**
     * @param null|string $language_code
     * @return User
     */
    public function setLanguageCode(?string $language_code): self
    {
        $this->language_code = $language_code;
        return $this;
    }

    /**
     * @param null|string $username
     * @return User
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param null|string $last_name
     * @return User
     */
    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param null|string $first_name
     * @return User
     */
    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param bool|null $is_bot
     * @return User
     */
    public function setIsBot(?bool $is_bot): self
    {
        $this->is_bot = $is_bot;
        return $this;
    }

    /**
     * @param int|null $id
     * @return User
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param array|null $users
     * @return array|null
     */
    public static function parseUsers(?array $users): ?array
    {
        if (is_null($users)) return null;
        return array_map(['self', 'parseUser'], $users);
    }

}