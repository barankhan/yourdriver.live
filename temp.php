<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/10/19
 * Time: 6:14 PM
 */
require_once __DIR__."/model/user.php";
require_once __DIR__."/utils/firebaseNotification.php";

$userObj = new User();
$driver = $userObj->getUserWithMobile("03216310881");

$fbaseObj = new firebaseNotification();

$notification['title']='Ride Alert';
$notification['body']='Passenger is waiting for you.';
$payload['do']="ride_alert";
$payload['msg']="You have a new ride";
$payload['key']="ride_alert";

$token = $driver->getFirebaseToken();

$fabseRes = $fbaseObj->sendPayloadOnly($token,$payload,$notification);

