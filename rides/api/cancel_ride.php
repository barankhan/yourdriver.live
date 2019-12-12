<?php
require_once __DIR__."/../../model/LogRequest.php";
require_once __DIR__."/../../model/transactions.php";
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/ride.php";
require_once __DIR__."/../../model/rideAlert.php";
require_once __DIR__."/../../utils/firebaseNotification.php";
require_once __DIR__."/../../utils/basePrice.php";
$mobile= $_REQUEST['mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();
$passengerObj = new User();
$passengerObj->getUserWithMobile($mobile);

$rideObj = new ride();
$rideObj->setId($_REQUEST['ride_id']);
$rideObj->findRideWithId();

if($rideObj->getIsRideStarted()==0 && $rideObj->getIsRideCancelled()==0){
    $rideObj->setIsRideCancelled(1);
    $rideObj->setRideCancelledAt(date("Y-m-d H:i:s"));
    $rideObj->update();
    $rideObj->setResponse("ride_cancelled_successfully");
    $basePrice = basePrice::getBasePrice($rideObj->getVehicleType(),$rideObj->getPickupLat(),$rideObj->getPickupLng());

    $tranObj = new DriverTransaction();
    $tranObj->setRideId($rideObj->getId());
    $tranObj->setPassengerId($rideObj->getPassengerId());
    $tranObj->setDriverId($rideObj->getDriverId());
    $tranObj->setTransactionType("cancelled_ride");
    $tranObj->setDriverStartUpFare($basePrice["driver_start_up_fare"]);
    $tranObj->setCompanyServiceCharges($basePrice["company_service_charges"]);
    $tranObj->setTimeElapsedRate($basePrice["time_elapsed_rate"]);
    $tranObj->setKmTravelledRate($basePrice["km_travelled_rate"]);
    $tranObj->setTotalFare();
    $tranObj->setTotalAmount();
    $tranObj->insertCanceledRide();

    $passengerObj->setBalance($passengerObj->getBalance()-$tranObj->getTotalFare());
    $passengerObj->update();

    $driverObj = new User();
    $driverObj->getUserWithId($rideObj->getDriverId());


    $fbaseObj = new firebaseNotification();

    $notification['title']='Ride Cancelled';
    $notification['body']='Ride Cancelled By the Passenger';
    $payload['do']="ride_cancelled";
    $payload['msg']="Ride Cancelled By the Passenger";
    $payload['key']="ride_cancelled";
    $payload['ride_id'] = $rideObj->getId();
    $token = $driverObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification);


}else{
    $rideObj->setResponse("ride_cancel_error");
}
$var = json_encode($rideObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




