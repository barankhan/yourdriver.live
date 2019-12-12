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

    private $id=0,$passengerId,$driverId,$createdAt,$updatedAt,$pickupLat,$pickupLng,$vehicleType,$dropoffLat
    ,$dropoffLng,$response,$message,$isRideStarted=0,$isRideCancelled=0,$rideStartedAt,$rideCancelledAt;

    public function insert(){
        $q = "insert into rides(passenger_id,pickup_lat,pickup_lng,vehicle_type,dropoff_lat,dropoff_lng)
            values(:passenger_id,:pickup_lat,:pickup_lng,:vehicle_type,:dropoff_lat,:dropoff_lng);";
        $params = array("passenger_id"=>$this->passengerId,"pickup_lat"=>$this->pickupLat,"pickup_lng"=>$this->pickupLng
        ,"vehicle_type"=>$this->vehicleType,"dropoff_lat"=>$this->dropoffLat,"dropoff_lng"=>$this->dropoffLng);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q = "update rides set is_ride_started=:isRideStarted,is_ride_cancelled=:isRideCancelled,ride_started_at=:rideStartedAt,
ride_cancelled_at=:rideCancelledAt where id=:id";
        $params = array("isRideStarted"=>$this->isRideStarted,"isRideCancelled"=>$this->isRideCancelled,
            "rideStartedAt"=>$this->rideStartedAt,"rideCancelledAt"=>"$this->rideCancelledAt","id"=>$this->id);
        return $this->executeUpdate($q,$params);

    }






    public function assignRideToDriver($id,$driver_id){
        try {
            $this->conn->beginTransaction();
            $q = "select * from rides where id=:id and COALESCE(driver_id,0)=0 FOR UPDATE;";
            $statement = $this->conn->prepare($q);
            $statement->execute(array("id" => $id));
            $records = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (sizeof($records) > 0) {
                $this->setAllFields($records[0]);

                $q3 = "update rides set driver_id=:ddriver_id where id=:idd;";
                $statement = $this->conn->prepare($q3);
                $params = array("idd" => $id, "ddriver_id" => $driver_id);
                $statement->execute($params);


                $q2 = "update ride_alerts set is_accepted=1,accepted_at=now() where ride_id=:id and driver_id=:driver_id;";
                $statement = $this->conn->prepare($q2);
                $statement->execute(array("id" => $id, "driver_id" => $driver_id));

                $this->conn->commit();
                return "driver_assigned";

            } else {
                $this->conn->commit();
                return "ride_already_assigned";
            }

        }catch (Exception $e){
            var_dump($e);
            die("fucked");
        }
    }


    public function findRideWithId(){
        $q = "select * from rides where id=:id";
        $params= array("id"=>$this->id);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }

    /**
     * @return int
     */
    public function getIsRideStarted()
    {
        return $this->isRideStarted;
    }

    /**
     * @param int $isRideStarted
     */
    public function setIsRideStarted($isRideStarted)
    {
        $this->isRideStarted = $isRideStarted;
    }

    /**
     * @return int
     */
    public function getIsRideCancelled()
    {
        return $this->isRideCancelled;
    }

    /**
     * @param int $isRideCancelled
     */
    public function setIsRideCancelled($isRideCancelled)
    {
        $this->isRideCancelled = $isRideCancelled;
    }

    /**
     * @return mixed
     */
    public function getRideStartedAt()
    {
        return $this->rideStartedAt;
    }

    /**
     * @param mixed $rideStartedAt
     */
    public function setRideStartedAt($rideStartedAt)
    {
        $this->rideStartedAt = $rideStartedAt;
    }

    /**
     * @return mixed
     */
    public function getRideCancelledAt()
    {
        return $this->rideCancelledAt;
    }

    /**
     * @param mixed $rideCancelledAt
     */
    public function setRideCancelledAt($rideCancelledAt)
    {
        $this->rideCancelledAt = $rideCancelledAt;
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
        unset($vars['conn']);
        return $vars;
    }
}