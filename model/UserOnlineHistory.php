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
        $q = "INSERT INTO `driver`.`user_online_history` (`user_id`, `online_at`, `offline_at`, `duration_in_minutes`) VALUES (:user_id, :online_at, :offline_at, :duration_in_minutes); ";
        $params=array("user_id"=>$this->userId, "online_at"=>$this->onlineAt, "offline_at"=>$this->offlineAt, "duration_in_minutes"=>$this->durationInMinutes);
        $this->setId($this->executeInsert($q,$params));
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