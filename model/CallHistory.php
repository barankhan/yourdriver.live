<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/27/19
 * Time: 2:25 PM
 */
class CallHistory extends  baseModel implements JsonSerializable
{

//    private id,to_user_id,from_user_id,ride_id,channel,provider,is_call_attended,created_at,updated_at;
    private $id,$toUserId,$fromUserId,$rideId,$channel,$provider='agora',$isCallAttended=0,$createdAt,$updatedAt;

    public function insert(){
        $q = "INSERT INTO `driver`.`call_history` (`to_user_id`, `from_user_id`, `ride_id`, `channel`, `provider`) VALUES (:to_user_id, :from_user_id,:ride_id, :channel, :provider); ";
        $params = array("to_user_id"=>$this->toUserId, "from_user_id"=>$this->fromUserId,"ride_id"=>$this->rideId, "channel"=>$this->channel, "provider"=>$this->provider);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q = "UPDATE `driver`.`call_history` SET `to_user_id` = :to_user_id, `from_user_id` = :from_user_id, `ride_id` = :ride_id, `channel` = :channel, `provider` = :provider, `is_call_attended` = :is_call_attended  WHERE `id` = :id; ";
        $params = array("to_user_id"=>$this->toUserId, "from_user_id"=>$this->fromUserId,"ride_id"=>$this->rideId, "channel"=>$this->channel, "provider"=>$this->provider,"is_call_attended"=>$this->isCallAttended,"id"=>$this->id);
        return $this->executeUpdate($q,$params);
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
    public function getToUserId()
    {
        return $this->toUserId;
    }

    /**
     * @param mixed $toUserId
     */
    public function setToUserId($toUserId)
    {
        $this->toUserId = $toUserId;
    }

    /**
     * @return mixed
     */
    public function getFromUserId()
    {
        return $this->fromUserId;
    }

    /**
     * @param mixed $fromUserId
     */
    public function setFromUserId($fromUserId)
    {
        $this->fromUserId = $fromUserId;
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
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getIsCallAttended()
    {
        return $this->isCallAttended;
    }

    /**
     * @param mixed $isCallAttended
     */
    public function setIsCallAttended($isCallAttended)
    {
        $this->isCallAttended = $isCallAttended;
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