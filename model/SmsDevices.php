<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 11/26/19
 * Time: 10:55 AM
 */

require_once "baseModel.php";

class SmsDevices extends  baseModel {

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

}

