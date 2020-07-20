<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 7/7/20
 * Time: 3:52 PM
 */
require_once __DIR__."/../vendor/autoload.php";






$userObj = new User();
$drivers = $userObj->getAllDrivers(" and city='Bahawalpur' and is_driver_online<>1 ",1,500);


foreach ($drivers as $driver){
    $newUserObj = new User();
    $newUserObj->setAllFields($driver);
    $smsQueueObj = new SMSQueue();
    $smsQueueObj->setNumber($newUserObj->getMobile());
    $smsQueueObj->setMessage("Dear ".$newUserObj->getName()." Kindly online ho jain... Shukriya....");
    $smsQueueObj->setSendBy(5);
    $smsQueueObj->insert();
}


