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

}

