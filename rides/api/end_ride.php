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
$transObj = new DriverTransaction();

if($rideObj->getIsRideEnded()==0) {
    $rideObj->setIsRideEnded(1);
    $rideObj->setRideEndedAt(date("Y-m-d H:i:s"));
    $rideObj->setDistance($_REQUEST['distance']);
    $rideObj->setRideEndedLat($_REQUEST['end_lat']);
    $rideObj->setRideEndedLng($_REQUEST['end_lng']);
    $rideObj->update();

    $passengerObj = new User();
    $passengerObj->getUserWithId($rideObj->getPassengerId());




    $basePrice = basePrice::getBasePrice($rideObj->getVehicleType(), $rideObj->getPickupLat(), $rideObj->getPickupLng());
    $transObj = Misc::generateCompletedRideTransaction($rideObj, $basePrice,$passengerObj,$driverObj);

    $driverObj->setIsDriverOnTrip(0);
    $driverObj->setTotalRides($driverObj->getTotalRides()+1);
    $driverObj->update();

    $transObj->setResponse("ride_ended_successfully");
    $transObj->setMessage("Your ride has been ended successfully");
    $transObj->setDriverBalance($driverObj->getBalance());
    $var = json_encode($transObj);
    echo $var;
    fastcgi_finish_request();

    $rideObj->setResponse("ride_Ended");
    $rideObj->setMessage("Thanks for the ride! We hope you have enjoyed the service.");



    $payload['message'] = "Thanks for using our service. Your fare is: ".$transObj->getTotalFare()." & Your payable amount is: ".$transObj->getPayableAmount();
    $payload['key'] = "p_ride_ended";
    $payload['ride'] = json_encode($rideObj);
    $fbaseObj = new firebaseNotification();
    $token = $passengerObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(), $token, $payload, null, 'high',2400000);
}else{
    $transObj->setResponse("ride_ended_error");
    $transObj->setMessage("Ride has already been ended.!");
    $var = json_encode($transObj);
    echo $var;
}



$lr->setResponseBody($var);
$lr->updateResponse();





