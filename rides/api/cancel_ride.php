<?php

require_once __DIR__."/../../model/LogRequest.php";
require_once __DIR__."/../../model/DriverTransaction.php";
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/ride.php";
require_once __DIR__."/../../model/rideAlert.php";
require_once __DIR__."/../../utils/firebaseNotification.php";
require_once __DIR__."/../../utils/basePrice.php";
require_once __DIR__."/../../utils/Misc.php";
require_once __DIR__."/../../utils/CooDistance.php";

$mobile= $_REQUEST['mobile'];

$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();
// find cancelled by user:

$userObj = new User();
$userObj->getUserWithMobile($mobile);

$rideObj = new ride();
$rideObj->setId($_REQUEST['ride_id']);
$rideObj->findRideWithId();



if($rideObj->getDriverId()==0){
    $rideObj->setResponse("ride_cancelled_successfully");
    $rideObj->setCancelledByTypeId(1);
    $rideObj->setIsRideCancelled(1);
    $rideObj->setRideCancelledAt(date('Y-m-d H:i:s'));
    Misc::generateCancelledTransaction($rideObj,null);
}else if($rideObj->getIsRideStarted()==0 && $rideObj->getIsRideCancelled()==0){
    $rideObj->setIsRideCancelled(1);
    $rideObj->setRideCancelledAt(date('Y-m-d H:i:s'));
    $cancelledByTypeId = ($userObj->getIsDriver()==1?2:1);
    $rideObj->setCancelledByTypeId($cancelledByTypeId);
    $rideObj->setResponse("ride_cancelled_successfully");
    $basePrice = basePrice::getBasePrice($rideObj->getVehicleType(),$rideObj->getPickupLat(),$rideObj->getPickupLng());
    if($userObj->getIsDriver()==1){
        // Cancelled By Driver.
        $passengerObj = new User();
        $passengerObj->getUserWithId($rideObj->getPassengerId());
        if($rideObj->getIsDriverArrived()==1){
            $cancTransObj = Misc::generateCancelledTransaction($rideObj,$basePrice);
            $passengerObj->setBalance($passengerObj->getBalance()-$cancTransObj->getTotalFare());
            $passengerObj->update();
        }else{
            $transObj = Misc::generateCancelledTransaction($rideObj,null);
            // TODO: Penalty to the Driver.
        }

        $fbaseObj = new firebaseNotification();
        $payload['msg']="Ride Cancelled By the Driver! Please Book a new ride.";
        $payload['key']="p_ride_cancelled";
        $token = $passengerObj->getFirebaseToken();
        $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification,'high',30);

        // Set On Trip Status.
        $userObj->setIsDriverOnTrip(0);
        $userObj->update();

    }else{
        // cancelled by user:
        $driverObj = new User();
        $driverObj->getUserWithId($rideObj->getDriverId());
        $meters = CooDistance::calculateDistanceBetweenTwoPoints($driverObj->getLat(),$driverObj->getLng(),$rideObj->getDriverLat(),$rideObj->getDriverLng(),'MT',true,5);

        // Add base fare to the User Balance in case of driver travelled 300 meters. otherwise create a transaction of the ride with 0 balance.
        if($meters>=300){
            $transObj = Misc::generateCancelledTransaction($rideObj,$basePrice);
            $userObj->setBalance($userObj->getBalance()-$transObj->getTotalFare());
            $userObj->update();
        }else{
            $transObj = Misc::generateCancelledTransaction($rideObj,null);
        }
        $fbaseObj = new firebaseNotification();


        $payload['message']="Ride Cancelled By the Passenger";
        $payload['key']="d_ride_cancelled";
        $token = $driverObj->getFirebaseToken();
        $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification,'high',30);

        // Set On Trip Status.
        $driverObj->setIsDriverOnTrip(0);
        $driverObj->update();

    }



}else{
    $rideObj->setResponse("ride_cancel_error");
}
$rideObj->update();
$var = json_encode($rideObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




