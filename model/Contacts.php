<?php
require_once __DIR__."/../vendor/autoload.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/10/20
 * Time: 1:03 PM
 */
class Contacts extends  baseModel implements JsonSerializable
{

    private $id=0,$contactNo, $sentBy,$sentCount=0,$createdAt,$updatedAt,$city;



    public function update(){
        $q = "UPDATE `contacts` SET `contact_no` = :contact_no, `sent_by` = :sent_by, `sent_count` = :sent_count WHERE `id` = :id;";
        $params = array("contact_no"=>$this->contactNo,"sent_by"=>$this->sentBy,"sent_count"=>$this->sentCount,"id"=>$this->id);
        return $this->executeUpdate($q,$params);
    }


    public function getNumberToSendSMS($sent_count=2){
        $q = "select * from contacts where sent_by=:sent_by and sent_count<$sent_count limit 1";
        $params = array("sent_by"=>$this->sentBy);
        $rs = $this->executeSelectSingle($q,$params);
        if($rs!=null){
            $this->setAllFields($rs);
        }
        if($this->id==0){
            $q = "select * from contacts where sent_count=0 limit 1";
            $rs = $this->executeSelectSingle($q);
            if($rs!=null){
                $this->setAllFields($rs);
            }
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

    public function __construct()
    {
        parent::__construct();

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
    public function getContactNo()
    {
        return $this->contactNo;
    }

    /**
     * @param mixed $contactNo
     */
    public function setContactNo($contactNo)
    {
        $this->contactNo = $contactNo;
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
    public function getSentCount()
    {
        return $this->sentCount;
    }

    /**
     * @param mixed $sentCount
     */
    public function setSentCount($sentCount)
    {
        $this->sentCount = $sentCount;
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