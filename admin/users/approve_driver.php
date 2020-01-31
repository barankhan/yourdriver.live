<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 6:10 PM
 */
require_once __DIR__."/../model/user.php";
require_once __DIR__."/../utils/firebaseNotification.php";

$userObj = new User();
$userObj->getUserWithId($_REQUEST['id']);
$userObj->setIsDriver(1);
$userObj->update();


$fbaseObj = new firebaseNotification();

$payload['message']="Congratulation! You're a stakeholder now, Please login.";
$payload['key']="driver_successful";
$token = $userObj->getFirebaseToken();

echo $fbaseObj->sendPayloadOnly(0,$token,$payload);