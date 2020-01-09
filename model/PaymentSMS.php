<?php
require_once "baseModel.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/9/20
 * Time: 2:39 PM
 */
class PaymentSMS extends  baseModel implements JsonSerializable
{

    private $id,$rawSms,$sender,$transactionId,$amount,$createdAt,$updatedAt,$isUsed=0,$usedBy,$usedAt;


    public function insert(){
        $q = "INSERT INTO `payment_sms`(`raw_sms`,`sender`,`transaction_id`,`amount`,`is_used`,`used_by`,`used_at`)VALUES(:raw_sms,:sender,:transaction_id,:amount,:is_used,:used_by,:used_at);";
        $params = array("raw_sms"=>$this->rawSms,"sender"=>$this->sender,"transaction_id"=>$this->transactionId,"amount"=>$this->amount
        ,"is_used"=>$this->isUsed,"used_by"=>$this->usedBy,"used_at"=>$this->usedAt);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q="UPDATE `payment_sms` SET `transaction_id` = :transaction_id ,`amount` = :amount, `is_used` = :is_used ,`used_by` = :used_by ,`used_at` = :used_at WHERE `id` = :id;";
        $params = array("transaction_id"=>$this->transactionId,"amount"=>$this->amount,"is_used"=>$this->isUsed,"used_by"=>$this->usedBy,"used_at"=>$this->usedAt,"id"=>$this->id);
        $this->executeUpdate($q,$params);
    }



    /**
     * PaymentSMS constructor.
     * @param $rawSms
     * @param $sender
     */
    public function __construct($rawSms, $sender)
    {
        parent::__construct();
        $this->rawSms = $rawSms;
        $this->sender = $sender;


        if($sender=='3737'){
            $rawSms = strtolower($rawSms);
            echo $rawSms = str_replace("rs ","rs. ",$rawSms);
            $s_array = explode(" ",$rawSms);
            $this->transactionId = str_replace(".","",$s_array[2]);
            $rs_array = explode("rs. ",$rawSms);
            $rs_array = explode(" ",$rs_array[1]);
            $this->amount =  str_replace(",",'',$rs_array[0]) ;
        }elseif($sender=="8558"){
            $s_array = explode(" ",$rawSms);
            $this->amount =  str_replace(",",'',$s_array[1]) ;
            $this->transactionId = str_replace(".",'',$s_array[count($s_array)-1]);
        }
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
    public function getRawSms()
    {
        return $this->rawSms;
    }

    /**
     * @param mixed $rawSms
     */
    public function setRawSms($rawSms)
    {
        $this->rawSms = $rawSms;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
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
    public function getIsUsed()
    {
        return $this->isUsed;
    }

    /**
     * @param int $isUsed
     */
    public function setIsUsed($isUsed)
    {
        $this->isUsed = $isUsed;
    }

    /**
     * @return mixed
     */
    public function getUsedBy()
    {
        return $this->usedBy;
    }

    /**
     * @param mixed $usedBy
     */
    public function setUsedBy($usedBy)
    {
        $this->usedBy = $usedBy;
    }

    /**
     * @return mixed
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }

    /**
     * @param mixed $usedAt
     */
    public function setUsedAt($usedAt)
    {
        $this->usedAt = $usedAt;
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