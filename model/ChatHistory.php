<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/27/19
 * Time: 2:25 PM
 */
class ChatHistory extends  baseModel implements JsonSerializable
{

//    private id,to_user_id,from_user_id,ride_id,channel,provider,is_call_attended,created_at,updated_at;
    private $id,$message,$senderId,$rideId,$createdAt,$updatedAt,$isMe;

    public function insert(){
        $q = "INSERT INTO `chat_history` (`message`, `ride_id`, `sender_id`) VALUES (:message,:ride_id, :sender_id); ";
        $params = array("message"=>$this->message, "sender_id"=>$this->senderId,"ride_id"=>$this->rideId);
        $this->setId($this->executeInsert($q,$params));
    }



    public function getRideChat(){
        $q = "Select * from chat_history where ride_id=:ride_id order by id";
        $params = array("ride_id"=>$this->rideId);
        return $this->executeSelect($q,$params);
    }

    /**
     * @return mixed
     */
    public function getIsMe()
    {
        return $this->isMe;
    }


    public function setIsMe($currentUserId)
    {
        $this->isMe = ($currentUserId==$this->senderId?true:false);
    }



    public function __construct()
    {
        parent::__construct();
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
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * @param mixed $senderId
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
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




    public function setAllFields($rs){
        foreach($rs as $key => $val) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = "set" . str_replace(" ", "", $key);
            $this->$key($val);
        }
    }



    public function jsonSerialize()
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