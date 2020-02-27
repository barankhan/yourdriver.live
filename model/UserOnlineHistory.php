<?php
require_once "baseModel.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/15/20
 * Time: 11:54 AM
 */
class UserOnlineHistory extends  baseModel implements JsonSerializable
{

//    private id,user_id,online_at,offline_at,duration_in_minutes,created_at,updated_at
    private $id,$userId,$onlineAt,$offlineAt,$durationInMinutes,$createdAt,$updatedAt;


    public function insert(){
        $q = "INSERT INTO `user_online_history` (`user_id`, `online_at`, `offline_at`, `duration_in_minutes`) VALUES (:user_id, :online_at, :offline_at, :duration_in_minutes); ";
        $params=array("user_id"=>$this->userId, "online_at"=>$this->onlineAt, "offline_at"=>$this->offlineAt, "duration_in_minutes"=>$this->durationInMinutes);
        $this->setId($this->executeInsert($q,$params));
    }


    public function getDailyReport($date){
        $q = "SELECT h.user_id as user_id,sum(duration_in_minutes) as duration FROM driver.user_online_history h,driver.users u
where h.user_id=u.id and date(h.created_at)=:ddate group by h.user_id  order by duration desc;";
        $params = array("ddate"=>$date);
        return $this->executeSelect($q,$params);
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
    public function getOnlineAt()
    {
        return $this->onlineAt;
    }

    /**
     * @param mixed $onlineAt
     */
    public function setOnlineAt($onlineAt)
    {
        $this->onlineAt = $onlineAt;
    }

    /**
     * @return mixed
     */
    public function getOfflineAt()
    {
        return $this->offlineAt;
    }

    /**
     * @param mixed $offlineAt
     */
    public function setOfflineAt($offlineAt)
    {
        $this->offlineAt = $offlineAt;
    }

    /**
     * @return mixed
     */
    public function getDurationInMinutes()
    {
        return $this->durationInMinutes;
    }

    /**
     * @param mixed $durationInMinutes
     */
    public function setDurationInMinutes($durationInMinutes)
    {
        $this->durationInMinutes = $durationInMinutes;
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
     * UserOnlineHistory constructor.
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
        // TODO: Implement jsonSerialize() method.
    }
}