<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 11/26/19
 * Time: 10:55 AM
 */

require_once "baseModel.php";

class SmsDevices extends  baseModel {

    private $id=0,$title,$token,$createdAt,$updatedAt,$simSlot,$deviceGroup;

    public function __construct(){
        parent::__construct();
    }

    public function getCurrentSMSSedingDevice(){
            $q = "select * from sms_devices where id=1;";
            return $this->executeSelectSingle($q);
    }


    public function updateDeviceToken($token){
        $q = "update sms_devices set token=:token where id=1;";
        $params = array("token"=>$token);
        return $this->executeUpdate($q,$params);
    }


    public function updateDeviceTokenWithId($token,$id){
        $q = "update sms_devices set token=:token where id=:id;";
        $params = array("token"=>$token,"id"=>$id);
        return $this->executeUpdate($q,$params);
    }


    public function updateDeviceTokenWithDeviceGroup($token,$id){
        $q = "update sms_devices set token=:token where device_group=:id;";
        $params = array("token"=>$token,"id"=>$id);
        return $this->executeUpdate($q,$params);
    }


    public function getSMSSendingDevice($id){
        $q = "select * from sms_devices where id=:id;";
        $this->setAllFields($this->executeSelectSingle($q,array("id"=>$id)));
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
    public function getDeviceGroup()
    {
        return $this->deviceGroup;
    }

    /**
     * @param mixed $deviceGroup
     */
    public function setDeviceGroup($deviceGroup)
    {
        $this->deviceGroup = $deviceGroup;
    }


    

    /**
     * @return mixed
     */
    public function getSimSlot()
    {
        return $this->simSlot;
    }

    /**
     * @param mixed $simSlot
     */
    public function setSimSlot($simSlot)
    {
        $this->simSlot = $simSlot;
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
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
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







}

