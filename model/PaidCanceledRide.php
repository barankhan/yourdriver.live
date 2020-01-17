<?php
require_once "baseModel.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/17/20
 * Time: 2:30 PM
 */
class PaidCanceledRide  extends  baseModel implements JsonSerializable
{

//    private id,transaction_id,cancelled_transaction_id,created_at,updated_at,is_successful
    private $id,$transactionId,$cancelledTransactionId,$createdAt,$updatedAt,$isSuccessful=0;


    public function insert(){
        $q = "INSERT INTO `paid_canceled_rides` (`transaction_id`, `cancelled_transaction_id`, `is_successful`) VALUES (:transaction_id, :cancelled_transaction_id, :is_successful); ";
        $params = array("transaction_id"=>$this->transactionId, "cancelled_transaction_id"=>$this->cancelledTransactionId, "is_successful"=>$this->isSuccessful);
        $this->setId($this->executeInsert($q,$params));

    }


    public function update(){
        $q = "UPDATE `paid_canceled_rides` SET `transaction_id` = :transaction_id, `cancelled_transaction_id` = :cancelled_transaction_id, `is_successful` = :is_successful WHERE `id` = :id; ";
        $params = array("transaction_id"=>$this->transactionId, "cancelled_transaction_id"=>$this->cancelledTransactionId, "is_successful"=>$this->isSuccessful,"id"=>$this->id);
        return $this->executeUpdate($q,$params);
    }


    public function getAllPaidCancelledRidesOfTransaction(){
        $q = "select * from paid_canceled_rides where transaction_id=:transaction_id and is_successful=0";
        $params = array("transaction_id"=>$this->transactionId);
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
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param mixed $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return mixed
     */
    public function getCancelledTransactionId()
    {
        return $this->cancelledTransactionId;
    }

    /**
     * @param mixed $cancelledTransactionId
     */
    public function setCancelledTransactionId($cancelledTransactionId)
    {
        $this->cancelledTransactionId = $cancelledTransactionId;
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
     * @return int
     */
    public function getIsSuccessful(): int
    {
        return $this->isSuccessful;
    }

    /**
     * @param int $isSuccessful
     */
    public function setIsSuccessful(int $isSuccessful)
    {
        $this->isSuccessful = $isSuccessful;
    }


    public function setAllFields($rs){

        foreach($rs as $key => $val) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = "set" . str_replace(" ", "", $key);
            $this->$key($val);
        }
    }



    /**
     * PaidCanceledRide constructor.
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