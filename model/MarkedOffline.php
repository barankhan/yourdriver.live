<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/21/20
 * Time: 8:55 AM
 */
class MarkedOffline   extends  baseModel implements JsonSerializable
{


    private $id=0,$driverId,$createdAt,$updateAt,$firebaseRequestReceived=0;



    public function insert(){
        $q="INSERT INTO `marked_offline`(`driver_id`,`firebase_request_received`)VALUES(:driver_id,:firebase_request_received);";
        $params = array("driver_id"=>$this->driverId,"firebase_request_received"=>$this->firebaseRequestReceived);
        return $this->setId($this->executeInsert($q,$params));
    }


    public function findById(){
        $q = "select * from marked_offline where id=:id";
        $params = array("id"=>$this->id);
        $this->setAllFields($this->executeSelectSingle($q,$params));
    }


    public function update(){
        $q = "UPDATE `marked_offline` SET  `driver_id` = :driver_id, `firebase_request_received` = :firebase_request_received WHERE `id` = :id; ";
        $params = array("driver_id"=>$this->driverId,"firebase_request_received"=>$this->firebaseRequestReceived,"id"=>$this->id);
        return $this->executeUpdate($q,$params);

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
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param mixed $updateAt
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }

    /**
     * @return mixed
     */
    public function getFirebaseRequestReceived()
    {
        return $this->firebaseRequestReceived;
    }

    /**
     * @param mixed $firebaseRequestReceived
     */
    public function setFirebaseRequestReceived($firebaseRequestReceived)
    {
        $this->firebaseRequestReceived = $firebaseRequestReceived;
    }









    /**
     * MarkedOffline constructor.
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