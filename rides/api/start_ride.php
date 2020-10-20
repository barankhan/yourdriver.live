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

if($rideObj->getIsRideStarted()==0) {
    $rideObj->setIsRideStarted(1);
    $rideObj->setRideStartedAt(date("Y-m-d H:i:s"));
    $rideObj->setRideStartedLat($_REQUEST['start_lat']);
    $rideObj->setRideStartedLng($_REQUEST['start_lng']);
    $rideObj->update();
    $rideObj->setResponse("ride_started");
    $rideObj->setMessage("Your Ride has been started! Enjoy the journey");
    $var = json_encode($rideObj);
    echo $var;
    //fastcgi_finish_request();

    $passengerObj = new User();
    $passengerObj->getUserWithId($rideObj->getPassengerId());


    $payload['message'] = "Your Ride has been started! Enjoy your journey.";
    $payload['key'] = "p_ride_started";
    $payload['ride'] = json_encode($rideObj);


    $fbaseObj = new firebaseNotification();


    $token = $passengerObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(), $token, $payload, null, 'normal',30);
}else{
    $rideObj->setResponse("ride_started_earlier");
    $rideObj->setMessage("you already have started the ride");
    $var = json_encode($rideObj);
    echo $var;

}


$lr->setResponseBody($var);
$lr->updateResponse();





