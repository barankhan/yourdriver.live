<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/12/19
 * Time: 3:14 PM
 */
class DriverTransaction extends  baseModel implements JsonSerializable
{
    private $id,$driverId,$passengerId,$transactionType,$driverStartUpFare,$companyServiceCharges,$timeElapsedMinutes=0,$timeElapsedRate,
$kmTravelled=0,$kmTravelledRate,$totalFare=0,$amountReceived=0,$createdAt,$updatedAt,$amountReceivedAt,$rideId,$totalAmount=0;




    public function insertCanceledRide(){
        $q = "insert into transactions (driver_id,passenger_id,transaction_type,driver_start_up_fare,company_service_charges,total_fare,
      ride_id,total_amount,km_travelled_rate,time_elapsed_rate) value(:driverId,:passengerId,:transactionType,:driverStartUpFare,:companyServiceCharges,:totalFare,
      :rideId,:totalAmount,kmTravelledRate,timeElapsedRate);";
        $params = array("driverId"=>$this->driverId,"passengerId"=>$this->passengerId,"transactionType"=>$this->transactionType,
            "driverStartUpFare"=>$this->driverStartUpFare,"companyServiceCharges"=>$this->companyServiceCharges,"totalFare"=>$this->totalFare,
      "rideId"=>$this->rideId,"totalAmount"=>$this->totalAmount,"kmTravelledRate"=>$this->kmTravelledRate,"timeElapsedRate"=>$this->timeElapsedRate);
        $this->setId($this->executeInsert($q,$params));
    }




    /**
     * @return int
     */
    public function getTimeElapsedMinutes()
    {
        return $this->timeElapsedMinutes;
    }

    /**
     * @param int $timeElapsedMinutes
     */
    public function setTimeElapsedMinutes($timeElapsedMinutes)
    {
        $this->timeElapsedMinutes = $timeElapsedMinutes;
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
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return mixed
     */
    public function getDriverStartUpFare()
    {
        return $this->driverStartUpFare;
    }

    /**
     * @param mixed $driverStartUpFare
     */
    public function setDriverStartUpFare($driverStartUpFare)
    {
        $this->driverStartUpFare = $driverStartUpFare;
    }

    /**
     * @return mixed
     */
    public function getCompanyServiceCharges()
    {
        return $this->companyServiceCharges;
    }

    /**
     * @param mixed $companyServiceCharges
     */
    public function setCompanyServiceCharges($companyServiceCharges)
    {
        $this->companyServiceCharges = $companyServiceCharges;
    }

    /**
     * @return mixed
     */
    public function getTimeElapsedRate()
    {
        return $this->timeElapsedRate;
    }

    /**
     * @param mixed $timeElapsedRate
     */
    public function setTimeElapsedRate($timeElapsedRate)
    {
        $this->timeElapsedRate = $timeElapsedRate;
    }

    /**
     * @return mixed
     */
    public function getKmTravelled()
    {
        return $this->kmTravelled;
    }

    /**
     * @param mixed $kmTravelled
     */
    public function setKmTravelled($kmTravelled)
    {
        $this->kmTravelled = $kmTravelled;
    }

    /**
     * @return mixed
     */
    public function getKmTravelledRate()
    {
        return $this->kmTravelledRate;
    }

    /**
     * @param mixed $kmTravelledRate
     */
    public function setKmTravelledRate($kmTravelledRate)
    {
        $this->kmTravelledRate = $kmTravelledRate;
    }

    /**
     * @return mixed
     */
    public function getTotalFare()
    {
        return $this->totalFare;
    }

    /**
     * @param mixed $totalFare
     */
    public function setTotalFare()
    {
        $this->totalFare = ($this->companyServiceCharges+$this->driverStartUpFare+($this->timeElapsedMinutes*$this->timeElapsedRate)+($this->kmTravelled*$this->kmTravelledRate));
    }

    /**
     * @return mixed
     */
    public function getAmountReceived()
    {
        return $this->amountReceived;
    }

    /**
     * @param mixed $amountReceived
     */
    public function setAmountReceived($amountReceived)
    {
        $this->amountReceived = $amountReceived;
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
    public function getAmountReceivedAt()
    {
        return $this->amountReceivedAt;
    }

    /**
     * @param mixed $amountReceivedAt
     */
    public function setAmountReceivedAt($amountReceivedAt)
    {
        $this->amountReceivedAt = $amountReceivedAt;
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
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param mixed $totalAmount
     */
    public function setTotalAmount()
    {
        $this->totalAmount = ($this->amountReceived-$this->totalFare)+$this->totalFare;
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
        unset($vars['conn']);
        return $vars;
    }
}