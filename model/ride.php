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
        $this->setId($this->executeUpdate($q,$params));
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