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

    private $id=0,$passengerId,$driverId=0,$createdAt,$updatedAt,$pickupLat,$pickupLng,$vehicleType,$dropoffLat
    ,$dropoffLng,$response,$message,$isRideStarted=0,$isRideCancelled=0,$rideStartedAt,$rideCancelledAt,$driverLat,$driverLng,
        $cancelledByTypeId=0,$isDriverArrived=0,$isRideEnded=0,$rideEndedAt,$driverArrivedAt,$distance,$rating,$pickupAddress,$dropoffAddress;
    public function insert(){
        $q = "insert into rides(passenger_id,pickup_lat,pickup_lng,vehicle_type,dropoff_lat,dropoff_lng,pickup_address,dropoff_address)
            values(:passenger_id,:pickup_lat,:pickup_lng,:vehicle_type,:dropoff_lat,:dropoff_lng,:pickup_address,:dropoff_address);";
        $params = array("passenger_id"=>$this->passengerId,"pickup_lat"=>$this->pickupLat,"pickup_lng"=>$this->pickupLng
        ,"vehicle_type"=>$this->vehicleType,"dropoff_lat"=>$this->dropoffLat,"dropoff_lng"=>$this->dropoffLng,"pickup_address"=>$this->pickupAddress,"dropoff_address"=>$this->dropoffAddress);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q = "update rides set is_ride_started=:isRideStarted,is_ride_cancelled=:isRideCancelled,ride_started_at=:rideStartedAt,
ride_cancelled_at=:rideCancelledAt,driver_lat=:driverLat,driver_lng=:driverLng,cancelled_by_type_id=:cancelledByTypeId,
is_driver_arrived=:isDriverArrived,driver_arrived_at=:driverArrivedAt,is_ride_ended=:isRideEnded,ride_ended_at=:rideEndedAt,
distance=:distance,rating=:rating,pickup_address=:pickup_address,dropoff_address=:dropoff_address
 
 where id=:id";
        $params = array("isRideStarted"=>$this->isRideStarted,"isRideCancelled"=>$this->isRideCancelled,
            "rideStartedAt"=>$this->rideStartedAt,"rideCancelledAt"=>$this->rideCancelledAt,"id"=>$this->id,
            "driverLat"=>$this->driverLat,"driverLng"=>$this->driverLng,"cancelledByTypeId"=>$this->cancelledByTypeId,
            "isDriverArrived"=>$this->isDriverArrived,"driverArrivedAt"=>$this->driverArrivedAt,"isRideEnded"=>$this->isRideEnded,
            "rideEndedAt"=>$this->rideEndedAt,"distance"=>$this->distance,"rating"=>$this->rating,"pickup_address"=>$this->pickupAddress,"dropoff_address"=>$this->dropoffAddress
        );

        return $this->executeUpdate($q,$params);

    }






    public function assignRideToDriver($id,$driver_id,$driverLat,$driverLng){
        try {

            $this->conn->beginTransaction();
            $q = "select * from rides where id=:id and COALESCE(driver_id,0)=0 and is_ride_cancelled=0 FOR UPDATE;";
            $statement = $this->conn->prepare($q);
            $statement->execute(array("id" => $id));
            $records = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (sizeof($records) > 0) {
                $this->setAllFields($records[0]);

                $q3 = "update rides set driver_id=:ddriver_id,driver_lat=:driverLat,driver_lng=:driverLng where id=:idd;";
                $statement = $this->conn->prepare($q3);
                $params = array("idd" => $id, "ddriver_id" => $driver_id,"driverLat"=>$driverLat,"driverLng"=>$driverLng);
                $statement->execute($params);


                $q3 = "update users set is_driver_on_trip=1,acceptance_points=acceptance_points+15 where id=:ddriver_id;";
                $statement = $this->conn->prepare($q3);
                $params = array("ddriver_id" => $driver_id);
                $statement->execute($params);

                $q2 = "update ride_alerts set is_accepted=1,accepted_at=now() where ride_id=:id and driver_id=:driver_id;";
                $statement = $this->conn->prepare($q2);
                $statement->execute(array("id" => $id, "driver_id" => $driver_id));
                $this->conn->commit();
                $this->setId($id);
                $this->findRideWithId();
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


    public function getNonAssignedRides(){
        $q = "SELECT * FROM driver.rides where coalesce(driver_id,0)=0 and coalesce(is_ride_cancelled)<>1 and TIMESTAMPDIFF(SECOND,created_at,now())>=8  order by id desc;";
        return $this->executeSelect($q);
    }

    /**
     * @return mixed
     */
    public function getPickupAddress()
    {
        return $this->pickupAddress;
    }

    /**
     * @param mixed $pickupAddress
     */
    public function setPickupAddress($pickupAddress)
    {
        $this->pickupAddress = $pickupAddress;
    }

    /**
     * @return mixed
     */
    public function getDropoffAddress()
    {
        return $this->dropoffAddress;
    }

    /**
     * @param mixed $dropoffAddress
     */
    public function setDropoffAddress($dropoffAddress)
    {
        $this->dropoffAddress = $dropoffAddress;
    }




    public function findRideWithId(){
        $q = "select * from rides where id=:id";
        $params= array("id"=>$this->id);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }





    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }



    /**
     * @return int
     */
    public function getIsRideEnded()
    {
        return $this->isRideEnded;
    }

    /**
     * @param int $isRideEnded
     */
    public function setIsRideEnded($isRideEnded)
    {
        $this->isRideEnded = $isRideEnded;
    }

    /**
     * @return mixed
     */
    public function getRideEndedAt()
    {
        return $this->rideEndedAt;
    }

    /**
     * @param mixed $rideEndedAt
     */
    public function setRideEndedAt($rideEndedAt)
    {
        $this->rideEndedAt = $rideEndedAt;
    }

    /**
     * @return mixed
     */
    public function getDriverArrivedAt()
    {
        return $this->driverArrivedAt;
    }

    /**
     * @param mixed $driverArrivedAt
     */
    public function setDriverArrivedAt($driverArrivedAt)
    {
        $this->driverArrivedAt = $driverArrivedAt;
    }



    /**
     * @return mixed
     */
    public function getDriverLat()
    {
        return $this->driverLat;
    }

    /**
     * @param mixed $driverLat
     */
    public function setDriverLat($driverLat)
    {
        $this->driverLat = $driverLat;
    }

    /**
     * @return mixed
     */
    public function getDriverLng()
    {
        return $this->driverLng;
    }

    /**
     * @param mixed $driverLng
     */
    public function setDriverLng($driverLng)
    {
        $this->driverLng = $driverLng;
    }

    /**
     * @return int
     */
    public function getCancelledByTypeId()
    {
        return $this->cancelledByTypeId;
    }

    /**
     * @param int $cancelledByTypeId
     */
    public function setCancelledByTypeId($cancelledByTypeId)
    {
        $this->cancelledByTypeId = $cancelledByTypeId;
    }

    /**
     * @return int
     */
    public function getIsDriverArrived()
    {
        return $this->isDriverArrived;
    }

    /**
     * @param int $isDriverArrived
     */
    public function setIsDriverArrived($isDriverArrived)
    {
        $this->isDriverArrived = $isDriverArrived;
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
//        $vars = array_filter(
//            get_object_vars($this),
//            function ($item) {
//                // Keep only not-NULL values
//                return !is_null($item);
//            }
//        );


        $vars = get_object_vars($this);

        array_walk($vars, function(&$v, $k) use (&$vars){
            $v = trim($v);
            if (empty($v))
                unset($vars[$k]);
        });


        unset($vars['conn']);
        $vars['createdAt']= date('h:i:s A', strtotime($vars['createdAt']));;
        if(array_key_exists('rideStartedAt', $vars))$vars['rideStartedAt']= date('h:i:s A', strtotime($vars['rideStartedAt']));;
        if(array_key_exists('rideEndedAt', $vars))$vars['rideEndedAt']= date('h:i:s A', strtotime($vars['rideEndedAt']));;
        if(array_key_exists('driverArrivedAt', $vars)) $vars['driverArrivedAt']= date('h:i:s A', strtotime($vars['driverArrivedAt']));;

        return $vars;
    }
}