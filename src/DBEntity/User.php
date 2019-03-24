<?php


namespace TelegramBot\DBEntity;

/**
 * @Entity @Table(name="users")
 **/
class User
{
    /** @Id @Column(type="integer") * */
    protected $id;
    /** @Column(type="string", nullable=false) * */
    protected $language_code;
    /** @Column(type="boolean") * */
    protected $blocked;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->language_code;
    }

    /**
     * @param string $language_code
     */
    public function setLanguageCode($language_code)
    {
        $this->language_code = $language_code;
    }

}