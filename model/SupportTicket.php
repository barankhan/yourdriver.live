<?php

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/18/20
 * Time: 3:43 PM
 */
class SupportTicket extends  baseModel implements JsonSerializable {




//    private $id,title,created_at,updated_at,is_closed,user_id,ride_id
    private $id=0,$title,$createdAt,$updatedAt,$isClosed,$userId,$rideId,$closedAt,$isUnread;


    public function insert(){
        $q = "INSERT INTO `support_tickets` ( `title`, `is_closed`, `user_id`, `ride_id`,`closed_at`,`is_unread`) VALUES (:title, :is_closed, :user_id, :ride_id,:closed_at,:is_unread); ";
        $params = array("title"=>$this->title, "is_closed"=>$this->isClosed, "user_id"=>$this->userId, "ride_id"=>$this->rideId,"closed_at"=>$this->closedAt,"is_unread"=>$this->isUnread);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q="UPDATE `support_tickets` SET `title` = :title, `is_closed` = :is_closed, `user_id` = :user_id, `ride_id` = :ride_id,closed_at=:closed_at,is_unread=:is_unread WHERE `id` = :id; ";
        $params = array("title"=>$this->title, "is_closed"=>$this->isClosed, "user_id"=>$this->userId, "ride_id"=>$this->rideId,"id"=>$this->id,"closed_at"=>$this->closedAt,"is_unread"=>$this->isUnread);
        $this->executeUpdate($q,$params);
    }



    public function getSupportTicketById(){
        $q = "select  * from support_tickets where id=:id";
        $params = array("id"=>$this->id);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }



    public function getUserSupportTickets($page,$limit=20){
        $q = "select * from support_tickets where user_id=:user_id order by created_at desc limit ".($page*$limit).",".$limit.";";
        $params = array("user_id"=>$this->userId);
        return $this->executeSelect($q,$params);
    }


    public function getLatestTickets(){
        $q = "select * from support_tickets s,support_ticket_history h where s.is_closed=0 and s.id=h.support_ticket_id order by h.created_at";
    }



    public function getSupportTicketOfRide(){
        $q = "select * from support_tickets where ride_id=:ride_id ;";
        $params = array("ride_id"=>$this->rideId);
        $this->setAllFields($this->executeSelectSingle($q,$params));

        if($this->id==0){
            $this->setTitle("Issue Reported against ride id: ".$this->rideId);
            $this->insert();
        }
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
     * @return mixed
     */
    public function getIsUnread()
    {
        return $this->isUnread;
    }

    /**
     * @param mixed $isUnread
     */
    public function setIsUnread($isUnread)
    {
        $this->isUnread = $isUnread;
    }



    
    /**
     * @return mixed
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param mixed $closedAt
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getIsClosed()
    {
        return $this->isClosed;
    }

    /**
     * @param mixed $isClosed
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
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
    public function getRideId()
    {
        return $this->rideId;
    }

    /**
     * @param mixed $rideId
     */
    public function setRideId($rideId)
    {
        $this->rideId = $rideId;
    }

    /**
     * SupportTicket constructor.
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

        $vars['createdAt']= date('d-m-y h:i:s A', strtotime($vars['createdAt']));
        unset($vars['conn']);
        return $vars;
    }
}