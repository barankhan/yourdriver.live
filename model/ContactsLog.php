<?php
require_once __DIR__."/../vendor/autoload.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/10/20
 * Time: 1:03 PM
 */
class ContactsLog extends  baseModel implements JsonSerializable
{

    private $id=0,$contactId,$sentBy,$createdAt,$updatedAt,$isSent=0;



    public function update(){
        $q = "UPDATE `contacts_log` SET `contact_id` = :contact_id, `sent_by` = :sent_by,`is_sent` =:is_sent WHERE `id` = :id; ";
        $params = array("contact_id"=>$this->contactId,"sent_by"=>$this->sentBy,"is_sent"=>$this->isSent,"id"=>$this->id);
        return $this->executeUpdate($q,$params);
    }



    public function insert(){
        $q = "INSERT INTO `contacts_log` (`contact_id`, `sent_by`) VALUES (:contact_id, :sent_by); ";
        $params = array("contact_id"=>$this->contactId,"sent_by"=>$this->sentBy);
        $this->setId($this->executeInsert($q,$params));
    }

    public function getTodayCountOfSender(){
        $q = "select count(*) as ct from contacts_log where sent_by=:sent_by and date(created_at)=date(now())";
        $params = array("sent_by"=>$this->sentBy);
        $rs = $this->executeSelectSingle($q,$params);
        return $rs['ct'];
    }


    public function getLast15MinutesCountOfSender(){
        $q = "select count(*) as ct from contacts_log where sent_by=:sent_by and created_at > NOW() - INTERVAL 15 MINUTE";
        $params = array("sent_by"=>$this->sentBy);
        $rs = $this->executeSelectSingle($q,$params);
        return $rs['ct'];
    }






    public function setAllFields($rs){

        foreach($rs as $key => $val) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = "set" . str_replace(" ", "", $key);
            $this->$key($val);
        }
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
     * @return mixed
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * @param mixed $contactId
     */
    public function setContactId($contactId)
    {
        $this->contactId = $contactId;
    }

    /**
     * @return mixed
     */
    public function getSentBy()
    {
        return $this->sentBy;
    }

    /**
     * @param mixed $sentBy
     */
    public function setSentBy($sentBy)
    {
        $this->sentBy = $sentBy;
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