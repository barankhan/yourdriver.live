<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 7/7/20
 * Time: 3:52 PM
 */
require_once __DIR__."/../vendor/autoload.php";






$userObj = new User();
$drivers = $userObj->getAllDrivers(" and vehicle_type<>'Auto' and is_driver_online=0",1,500);


foreach ($drivers as $driver){
    $newUserObj = new User();
    $newUserObj->setAllFields($driver);
    $smsQueueObj = new SMSQueue();
    $smsQueueObj->setNumber($newUserObj->getMobile());
    $smsQueueObj->setMessage("Dear ".$newUserObj->getName()." apni location her waqt update rakhnay ky liye Application ki setting karin: https://yourdriver.live/bo/ shukriya. ");
    $smsQueueObj->setSendBy(4);
    $smsQueueObj->insert();
}


