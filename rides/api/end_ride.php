<?php
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();
$driverObj = new User();
$driverObj->getUserWithMobile($mobile);
$rideObj = new ride();
$rideObj->setId($_REQUEST['ride_id']);
$rideObj->findRideWithId();
$rideObj->setIsRideEnded(1);
$rideObj->setRideEndedAt(date("Y-m-d H:i:s"));
$rideObj->update();
$rideObj->setResponse("ride_started");
$rideObj->setMessage("Your Ride has been started! Enjoy the journey");

$passengerObj  = new User();
$passengerObj->getUserWithId($rideObj->getPassengerId());


$notification['title']='You are on your way!';
$notification['body']='Your Ride has been started! Enjoy the journey';
$payload['do']="ride_started";
$payload['msg']="Your Ride has been started! Enjoy the journey";
$payload['key']="ride_started";

$fbaseObj = new firebaseNotification();


$token = $passengerObj->getFirebaseToken();
$fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification,'normal');


$var = json_encode($rideObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




