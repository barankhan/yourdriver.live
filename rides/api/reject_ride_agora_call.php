<?php
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();
$fromUserObj = new User();
$fromUserObj->getUserWithMobile($mobile);


$rideObj =  new ride();
$rideObj->setId($_REQUEST['ride_id']);
$rideObj->findRideWithId();


if($rideObj->getPassengerId()==$fromUserObj->getId()){
    // Rejected by the Passenger Now Inform Driver.

    $toUserObj = new User();
    $toUserObj->getUserWithId($rideObj->getDriverId());



}else{
    $toUserObj = new User();
    $toUserObj->getUserWithId($rideObj->getPassengerId());

}

$var = json_encode(array("message"=>"Call ended","response"=>"call_ended"));
echo $var;
fastcgi_finish_request();


$fbaseObj = new firebaseNotification();
$payload['ride_id']="".$_REQUEST['ride_id'];
$payload['key']="call_rejected";
$payload['message']="Call rejected";
$token = $toUserObj->getFirebaseToken();
$fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,'high');

$lr->setResponseBody($var);
$lr->updateResponse();





