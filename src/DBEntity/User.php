<?php


namespace TelegramBot\DBEntity;

/**
 * @Entity @Table(name="users")
 **/
class User
{
    /** @Id @Column(type="integer") * */
    protected $id;
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

}