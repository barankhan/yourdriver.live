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

$notification['title']='Congrats';
$notification['body']='you are partner now!.';
$notification['click_action']='com.barankhan.driver.driver_confirmation_activity';

$payload['do']="logout";
$payload['msg']="Congratulation. Please login now.";
$payload['key']="driver_successful";
$token = $userObj->getFirebaseToken();

echo $fbaseObj->sendPayloadOnly($token,$payload,$notification);