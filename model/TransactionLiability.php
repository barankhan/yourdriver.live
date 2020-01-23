<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/23/20
 * Time: 4:27 PM
 */
class TransactionLiability  extends  baseModel implements JsonSerializable
{


    private $id=0,$liabilityTypeId,$title,$amount,$transactionId,$createdAt,$updatedAt;


    public function insert(){
        $q = "INSERT INTO `transaction_liabilities` (`liability_type_id`, `title`, `amount`, `transaction_id`) VALUES (:liability_type_id, :title,  :amount, :transaction_id);";
        $params = array("liability_type_id"=>$this->liabilityTypeId, "title"=>$this->title,  "amount"=>$this->amount, "transaction_id"=>$this->transactionId);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q = "UPDATE `driver`.`transaction_liabilities` SET `liability_type_id` = :liability_type_id, `title` = :title, `amount` = :amount, `transaction_id` = :transaction_id WHERE `id` = :id;";
        $params = array("liability_type_id"=>$this->liabilityTypeId, "title"=>$this->title,  "amount"=>$this->amount, "transaction_id"=>$this->transactionId,"id"=>$this->id);
        return $this->executeUpdate($q,$params);
    }


    public function findById(){
        $q = "select * from `transaction_liabilities` where id=:id";
        $params = array("id"=>$this->id);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }


    public function findByTransactionId(){
        $q = "select * from `transaction_liabilities` where transaction_id=:transaction_id";
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
    public function getLiabilityTypeId()
    {
        return $this->liabilityTypeId;
    }

    /**
     * @param mixed $liabilityTypeId
     */
    public function setLiabilityTypeId($liabilityTypeId)
    {
        $this->liabilityTypeId = $liabilityTypeId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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


    public function setAllFields($rs){

        foreach($rs as $key => $val) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = "set" . str_replace(" ", "", $key);
            $this->$key($val);
        }
    }





    /**
     * TransactionLiability constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    function jsonSerialize()
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