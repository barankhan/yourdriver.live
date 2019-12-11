<?php
require_once "baseModel.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/10/19
 * Time: 2:33 PM
 */
class ride extends  baseModel implements JsonSerializable
{

    private $id=0,$passengerId,$driverId,$createdAt,$updatedAt,$pickupLat,$pickupLng,$vehicleType,$dropoffLat,$dropoffLng,$response,$message;

    public function insert(){
        $q = "insert into rides(passenger_id,pickup_lat,pickup_lng,vehicle_type,dropoff_lat,dropoff_lng)
            values(:passenger_id,:pickup_lat,:pickup_lng,:vehicle_type,:dropoff_lat,:dropoff_lng);";
        $params = array("passenger_id"=>$this->passengerId,"pickup_lat"=>$this->pickupLat,"pickup_lng"=>$this->pickupLng
        ,"vehicle_type"=>$this->vehicleType,"dropoff_lat"=>$this->dropoffLat,"dropoff_lng"=>$this->dropoffLng);
        $this->setId($this->executeInsert($q,$params));
    }


    public function assignRideToDriver($id,$driver_id){
        $this->conn->beginTransaction();
            $q = "select * from rides where id=:id and COALESCE(driver_id,0)=0 for update";
            $this->conn->prepare(array("id"=>$id));
            $statement = $this->conn->exec($q);
            $records = $statement->fetchAll();
            if(sizeof($records)>0){
                $this->setAllFields($records[0]);
                $q2 = "update ride_alerts set is_accepted=1,accepted_at=now() where ride_id=:id and driver_id=:driver_id";
                $this->conn->prepare(array("id"=>$id,"driver_id"=>$driver_id));
                $this->conn->exec($q2);

                $q3 = "update ride set driver_id=:driver_id where id=:id";
                $this->conn->prepare(array("id"=>$id,"driver_id"=>$driver_id));
                $this->conn->exec($q3);

                return "driver_assigned";

            }else{
                return "ride_already_assigned";
            }
        $this->conn->commit();
    }


    public function findRideWithId(){
        $q = "select * from rides where id=:id";
        $params= array("id"=>$this->id);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }

    public function __construct(){
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
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
     * @return int
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
     * @return mixed
     */
    public function getPassengerId()
    {
        return $this->passengerId;
    }

    /**
     * @param mixed $passengerId
     */
    public function setPassengerId($passengerId)
    {
        $this->passengerId = $passengerId;
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
    public function getPickupLat()
    {
        return $this->pickupLat;
    }

    /**
     * @param mixed $pickupLat
     */
    public function setPickupLat($pickupLat)
    {
        $this->pickupLat = $pickupLat;
    }

    /**
     * @return mixed
     */
    public function getPickupLng()
    {
        return $this->pickupLng;
    }

    /**
     * @param mixed $pickupLng
     */
    public function setPickupLng($pickupLng)
    {
        $this->pickupLng = $pickupLng;
    }

    /**
     * @return mixed
     */
    public function getVehicleType()
    {
        return $this->vehicleType;
    }

    /**
     * @param mixed $vehicleType
     */
    public function setVehicleType($vehicleType)
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return mixed
     */
    public function getDropoffLat()
    {
        return $this->dropoffLat;
    }

    /**
     * @param mixed $dropoffLat
     */
    public function setDropoffLat($dropoffLat)
    {
        $this->dropoffLat = $dropoffLat;
    }

    /**
     * @return mixed
     */
    public function getDropoffLng()
    {
        return $this->dropoffLng;
    }

    /**
     * @param mixed $dropoffLng
     */
    public function setDropoffLng($dropoffLng)
    {
        $this->dropoffLng = $dropoffLng;
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