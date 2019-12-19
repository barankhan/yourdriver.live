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
$rideObj->setIsDriverArrived(1);
$rideObj->setDriverArrivedAt(date("Y-m-d H:i:s"));
$rideObj->update();
$rideObj->setResponse("driver_arrived");
$rideObj->setMessage("Driver arrived at your location");

$passengerObj  = new User();
$passengerObj->getUserWithId($rideObj->getPassengerId());


$notification['title']='Driver Hazir hy!';
$notification['body']='Driver is reached at your pickup location!';
$payload['do']="driver_arrived";
$payload['msg']="Driver is reached at your pickup location!";
$payload['key']="driver_arrived";

$fbaseObj = new firebaseNotification();


$token = $passengerObj->getFirebaseToken();
$fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification,'high');


$var = json_encode($rideObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




