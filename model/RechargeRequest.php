<?php
require_once __DIR__."/../vendor/autoload.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/9/20
 * Time: 2:39 PM
 */
class RechargeRequest extends  baseModel implements JsonSerializable
{

    private $id=0,$userId,$transactionId,$amount,$paymentType,$createdAt,$updatedAt,$isSuccessful=0,$isDeleted=0;


    public function insert(){
        $q = "INSERT INTO `recharge_requests`(`user_id`,`transaction_id`,`amount`,`payment_type`,`is_successful`,`is_deleted`)VALUES(:user_id,:transaction_id,:amount,:payment_type,:is_successful,:is_deleted);";
        $params = array("user_id"=>$this->userId,"transaction_id"=>$this->transactionId,"amount"=>$this->amount,"payment_type"=>$this->paymentType,
        "is_successful"=>$this->isSuccessful,"is_deleted"=>$this->isDeleted);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q="UPDATE `recharge_requests` SET  `user_id` = :user_id, `transaction_id` = :transaction_id, `amount` = :amount, `payment_type` = :payment_type, `is_successful` = :is_successful, `is_deleted` = :is_deleted WHERE `id` = :id; ";
        $params = array("user_id"=>$this->userId,"transaction_id"=>$this->transactionId,"amount"=>$this->amount,"payment_type"=>$this->paymentType,
            "is_successful"=>$this->isSuccessful,"is_deleted"=>$this->isDeleted,"id"=>$this->id);
        $this->executeUpdate($q,$params);
    }


    public function transactionExists(){
        $q = "select count(*) as ct from recharge_requests where payment_type=:payment_type and transaction_id=:transaction_id and amount=:amount
        and is_deleted=0 ";
        $params = array("payment_type"=>$this->paymentType,"transaction_id"=>$this->transactionId,"amount"=>$this->amount);
        $rs = $this->executeSelectSingle($q,$params);
        return $rs['ct'];
    }


    public function successfulTransactionExists(){
        $q = "select count(*) as ct from recharge_requests where payment_type=:payment_type and transaction_id=:transaction_id 
        and is_deleted=0 and is_successful=1";
        $params = array("payment_type"=>$this->paymentType,"transaction_id"=>$this->transactionId);
        $rs = $this->executeSelectSingle($q,$params);
        return $rs['ct'];
    }


    public function getNonSuccessfulTransaction(){
        $q = "select * from recharge_requests where payment_type=:payment_type and transaction_id=:transaction_id and amount=:amount
        and is_deleted=0 and is_successful=0";
        $params = array("payment_type"=>$this->paymentType,"transaction_id"=>$this->transactionId,"amount"=>$this->amount);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }






    public function validateTransactionAndInsert($currentUser){
        if($this->successfulTransactionExists()){
            // Transaction has already been performed by any users.
            return "transaction_already_successful";
        }else if($this->transactionExists()) {

            $this->getNonSuccessfulTransaction();
            if($this->getUserId()==$currentUser){
                return $this->processPayment();
            }else{
                return "invalid_request";
            }

        }else{
            $this->insert();
            return $this->processPayment();
        }
    }


    private function processPayment(){
        $paymentSMSObj = new PaymentSMS();
        $paymentSMSObj->setTransactionId($this->transactionId);
        $paymentSMSObj->setSender($this->getPaymentTypeSenderNumber($this->paymentType));
        $paymentSMSObj->setAmount($this->amount);
        $paymentSMSObj->getPaymentSMSToRedeem();
        if($paymentSMSObj->getId()>0 && $this->id>0){
                $paymentSMSObj->setIsUsed(1);
                $paymentSMSObj->setUsedBy($this->userId);
                $paymentSMSObj->setRechargeRequestId($this->id);
                $paymentSMSObj->setUsedAt(date("Y-m-d H:i:s"));
                $paymentSMSObj->update();
                $this->isSuccessful=1;
                $this->update();
                return "payment_done";
        }else{
            return "sms_not_found";
        }
    }





    private function getPaymentTypeSenderNumber(){
        switch ($this->paymentType){
            case "JazzCash":
                return "8558";
                break;
            case "Easypaisa":
                return "3737";
                break;
            case "Upaisa":
                return "1234";
                break;
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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param mixed $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
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
    public function getIsSuccessful()
    {
        return $this->isSuccessful;
    }

    /**
     * @param int $isSuccessful
     */
    public function setIsSuccessful($isSuccessful)
    {
        $this->isSuccessful = $isSuccessful;
    }

    /**
     * @return int
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param int $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
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
     * PaymentSMS constructor.
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