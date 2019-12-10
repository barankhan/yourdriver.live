<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/10/19
 * Time: 4:06 PM
 */
class rideAlert extends  baseModel implements JsonSerializable
{

    private $id,$driverId,$rideId,$isAccepted=0,$acceptedAt;


    public function insert(){
        $q = "insert into ride_alerts(driver_id,ride_id)values(:driverId,:rideId);";
        $params = array("driverId"=>$this->driverId,"rideId"=>$this->rideId);
        $this->setId($this->executeInsert($q,$params));
        return $this->getId();
    }


    public function update(){
        $q = "update ride_alerts set is_accepted=:isAccepted,accepted_at=now() where id=:id;";
        $params = array("isAccepted"=>$this->isAccepted,"id"=>$this->id);
        return $this->executeUpdate($q,$params);
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
    public function getDriverId()
    {
        return $this->driverId;
    }

    /**
     * @param mixed $driverId
     */
    public function setDriverId($driverId)
    {
        $this->driverId = $driverId;
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
     * @return int
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * @param int $isAccepted
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
    }

    /**
     * @return mixed
     */
    public function getAcceptedAt()
    {
        return $this->acceptedAt;
    }

    /**
     * @param mixed $acceptedAt
     */
    public function setAcceptedAt($acceptedAt)
    {
        $this->acceptedAt = $acceptedAt;
    }


    public function __construct(){
        parent::__construct();
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

        return $vars;
    }
}