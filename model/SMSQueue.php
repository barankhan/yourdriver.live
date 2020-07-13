<?php
require_once __DIR__."/../vendor/autoload.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 7/7/20
 * Time: 2:20 PM
 */
class SMSQueue extends  baseModel implements JsonSerializable
{

    private $id=0,$isSent=0, $message,$number,$createdAt,$updatedAt,$sentAt,$sendBy,$simSlot=99;


    public function insert(){
        $q = "INSERT INTO `driver`.`sms_queue`(`message`,`number`,`send_by`,`sim_slot`)VALUES(:message,:number,:send_by,:sim_slot);";
        $params = array("message"=>$this->message,"number"=>$this->number,"send_by"=>$this->sendBy,"sim_slot"=>$this->simSlot);
        return $this->id = $this->executeInsert($q,$params);
    }

    public function updateSent(){
        $q = "UPDATE `sms_queue` SET `is_sent` = :is_sent,`sent_at` = :sent_at WHERE `id` = :id;";
        $params = array("id"=>$this->id,"is_sent"=>$this->isSent,"sent_at"=>$this->sentAt);
        return $this->executeUpdate($q,$params);
    }


    public function getNumberToSendSMS(){
        $q = "select * from sms_queue where is_sent=0 and send_by=:send_by limit 1";
        $params = array("send_by"=>$this->sendBy);
        $rs = $this->executeSelectSingle($q,$params);
        if($rs!=null){
            $this->setAllFields($rs);
        }
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
     * @return int
     */
    public function getSimSlot(): int
    {
        return $this->simSlot;
    }

    /**
     * @param int $simSlot
     */
    public function setSimSlot(int $simSlot)
    {
        $this->simSlot = $simSlot;
    }




    public function __construct()
    {
        parent::__construct();

    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIsSent(): int
    {
        return $this->isSent;
    }

    /**
     * @param int $isSent
     */
    public function setIsSent(int $isSent)
    {
        $this->isSent = $isSent;
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
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
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
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @param mixed $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return mixed
     */
    public function getSendBy()
    {
        return $this->sendBy;
    }

    /**
     * @param mixed $sendBy
     */
    public function setSendBy($sendBy)
    {
        $this->sendBy = $sendBy;
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