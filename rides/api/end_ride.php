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
    $rideObj->update();

    $basePrice = basePrice::getBasePrice($rideObj->getVehicleType(), $rideObj->getPickupLat(), $rideObj->getPickupLng());
    $transObj = Misc::generateCompletedRideTransaction($rideObj, $basePrice);
    $driverObj->setBalance($driverObj->getBalance() - $transObj->getCompanyServiceCharges());
    $driverObj->setIsDriverOnTrip(0);
    $driverObj->update();
    $transObj->setResponse("ride_ended_successfully");
    $transObj->setMessage("Your ride has been ended successfully");
    $transObj->setDriverBalance($driverObj->getBalance());
    $rideObj->setResponse("ride_Ended");
    $rideObj->setMessage("Thanks for the ride! We hope you have enjoyed the service.");
    $passengerObj = new User();
    $passengerObj->getUserWithId($rideObj->getPassengerId());
    $payload['message'] = "Thanks for the ride! We hope you have enjoyed the service. Your fare is: ".$transObj->getTotalFare();
    $payload['key'] = "p_ride_ended";
    $fbaseObj = new firebaseNotification();
    $token = $passengerObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(), $token, $payload, null, 'high',60);
}else{
    $transObj->setResponse("ride_ended_error");
    $transObj->setMessage("Ride has already been ended.!");
}


$var = json_encode($transObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




