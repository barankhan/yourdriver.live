<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 7/7/20
 * Time: 3:52 PM
 */
require_once __DIR__."/../vendor/autoload.php";






$userObj = new User();
$drivers = $userObj->getAllDrivers(null,1,500);


foreach ($drivers as $driver){
    $newUserObj = new User();
    $newUserObj->setAllFields($driver);
    $smsQueueObj = new SMSQueue();
    $smsQueueObj->setNumber($newUserObj->getMobile());
    $smsQueueObj->setMessage("Dear ".$newUserObj->getName()." Ap sy guzarish hy ky Driver App per online howa karain. Ap ka bhot shukriya.");
    $smsQueueObj->setSendBy(4);
    $smsQueueObj->insert();
    $smsQueueObj = new SMSQueue();
    $smsQueueObj->setNumber($newUserObj->getMobile());
    $smsQueueObj->setMessage("Driver app per passenger ya driver refer karin or long term bonus paain ....https://yourdriver.live/referral/ ");
    $smsQueueObj->setSendBy(4);
    $smsQueueObj->insert();
}


