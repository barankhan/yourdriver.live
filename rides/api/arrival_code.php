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

if($rideObj->getArrivalCode()==0){
    $rideObj->setArrivalCode(mt_rand(10000,99999));
    $rideObj->update();
}
$rideObj->setResponse("code_sent");
$rideObj->setMessage("Code has been sent to the passenger!.");
$var = json_encode($rideObj);
echo $var;
fastcgi_finish_request();

$passengerObj = new User();
$passengerObj->getUserWithId($rideObj->getPassengerId());


$payload['message'] = "Arrival Code is: ".$rideObj->getArrivalCode();
$payload['key'] = "p_ride_started";
$payload['ride'] = json_encode($rideObj);


$fbaseObj = new firebaseNotification();


$token = $passengerObj->getFirebaseToken();
$fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(), $token, $payload, null, 'normal',30);



$lr->setResponseBody($var);
$lr->updateResponse();





