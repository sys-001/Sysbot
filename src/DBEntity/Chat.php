<?php


namespace TelegramBot\DBEntity;

/**
 * @Entity @Table(name="chats")
 **/
class Chat
{
    /** @Id @Column(type="bigint") * */
    protected $id;
    /** @Column(type="array", nullable=false) * */
    protected $admins;
    /** @Column(type="string", nullable=false) * */
    protected $type;

    /**
     * @return array
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    /**
     * @param array $admins
     */
    public function setAdmins($admins)
    {
        $this->admins = $admins;
    }

    /**
     * @return double
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param double $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


}