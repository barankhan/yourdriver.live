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
if($rideObj->getIsRideCancelled()==0){
    $rideObj->findRideWithId();
    $rideObj->setIsDriverArrived(1);
    $rideObj->setDriverArrivedAt(date("Y-m-d H:i:s"));
    $rideObj->update();
    $rideObj->setResponse("driver_arrived");
    $rideObj->setMessage("Driver arrived at your location");
    $var = json_encode($rideObj);
    echo $var;
    fastcgi_finish_request();

    $passengerObj  = new User();
    $passengerObj->getUserWithId($rideObj->getPassengerId());

    $payload['message']="Driver is reached at your pickup location! Please Meet him. (".$driverObj->getRegAlphabet()."-".$driverObj->getRegYear()."-".$driverObj->getRegNo().")";
    $payload['key']="p_driver_arrived";
    $payload['ride']=json_encode($rideObj);

    $fbaseObj = new firebaseNotification();


    $token = $passengerObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification,'high',200);

}else{
    $rideObj->setResponse("ride_canceled_by_passenger");
    $rideObj->setResponse("Sorry The Ride has been cancelled by the passenger");
    $var = json_encode($rideObj);
    echo $var;
}



$lr->setResponseBody($var);
$lr->updateResponse();





