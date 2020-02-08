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

    private $id,$driverId,$rideId,$isAccepted=0,$acceptedAt,$isRejected=0,$rejectedAt,$createdAt,$updatedAt;


    public function insert(){
        $q = "insert into ride_alerts(driver_id,ride_id)values(:driverId,:rideId);";
        $params = array("driverId"=>$this->driverId,"rideId"=>$this->rideId);
        $this->setId($this->executeInsert($q,$params));
        return $this->getId();
    }


    public function update(){
        $q = "update ride_alerts set is_accepted=:is_accepted,accepted_at=:accepted_at,rejected_at=:rejected_at,is_rejected=:is_rejected where id=:id;";
        $params = array("is_accepted"=>$this->isAccepted,"id"=>$this->id,"is_rejected"=>$this->isRejected,
            "rejected_at"=>$this->rejectedAt,"accepted_at"=>$this->acceptedAt);
        return $this->executeUpdate($q,$params);
    }

    public function findAlertByDriverId(){
        $q = "select * from ride_alerts where ride_id=:ride_id and driver_id=:driver_id";
        $params = array("ride_id"=>$this->rideId,"driver_id"=>$this->driverId);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }


    public function shouldCancelRideInCron($ride_id){
        $q = 'SELECT if(TIMESTAMPDIFF(SECOND,updated_at,now())>40,"1","0") as val  FROM driver.ride_alerts where ride_id='.$ride_id.' order by id desc limit 1;';
        $rs = $this->executeSelectSingle($q);

        if($rs['val']==1){
            return true;
        }else{
            return false;
        }

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
    public function getIsRejected()
    {
        return $this->isRejected;
    }

    /**
     * @param mixed $isRejected
     */
    public function setIsRejected($isRejected)
    {
        $this->isRejected = $isRejected;
    }

    /**
     * @return mixed
     */
    public function getRejectedAt()
    {
        return $this->rejectedAt;
    }

    /**
     * @param mixed $rejectedAt
     */
    public function setRejectedAt($rejectedAt)
    {
        $this->rejectedAt = $rejectedAt;
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