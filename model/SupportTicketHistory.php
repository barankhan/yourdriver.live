<?php

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/18/20
 * Time: 4:21 PM
 */
class SupportTicketHistory  extends  baseModel implements JsonSerializable {


    //id,support_ticket_id,message,created_at,updated_at,user_id,is_replied

    private $id,$supportTicketId,$message,$createdAt,$updatedAt,$userId,$isReplied;



    public function insert(){
        $q = "INSERT INTO `support_ticket_history` ( `support_ticket_id`, `message`,`user_id`, `is_replied`) VALUES (:support_ticket_id, :message, :user_id, :is_replied); ";
        $params = array("support_ticket_id"=>$this->supportTicketId, "message"=>$this->message, "user_id"=>$this->userId, "is_replied"=>$this->isReplied);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q = "UPDATE `support_ticket_history` SET `support_ticket_id` = :support_ticket_id, `message` = :message, `user_id` = :user_id, `is_replied` = :is_replied WHERE `id` = :id; ";
        $params = array("support_ticket_id"=>$this->supportTicketId, "message"=>$this->message, "user_id"=>$this->userId, "is_replied"=>$this->isReplied,"id"=>$this->id);
        return $this->executeUpdate($q,$params);

    }


    public function getSupportTicketHistory(){
        $q = "select * from support_ticket_history where support_ticket_id=:support_ticket_id";
        $params = array("support_ticket_id"=>$this->supportTicketId);
        return $this->executeSelect($q,$params);
    }

    public function getSupportTicketHistoryById(){
        $q = "select * from support_ticket_history where id=:id";
        $params = array("id"=>$this->id);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }






    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSupportTicketId()
    {
        return $this->supportTicketId;
    }

    /**
     * @param mixed $supportTicketId
     */
    public function setSupportTicketId($supportTicketId)
    {
        $this->supportTicketId = $supportTicketId;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getIsReplied()
    {
        return $this->isReplied;
    }

    /**
     * @param mixed $isReplied
     */
    public function setIsReplied($isReplied)
    {
        $this->isReplied = $isReplied;
    }




    public function setAllFields($rs){

        foreach($rs as $key => $val) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = "set" . str_replace(" ", "", $key);
            $this->$key($val);
        }
    }



    /**
     * SupportTicketHistory constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $vars = array_filter(
            get_object_vars($this),
            function ($item) {
                // Keep only not-NULL values
                return ! is_null($item);
            }
        );

        unset($vars['conn']);
        return $vars;
    }
}