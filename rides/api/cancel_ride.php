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

    $driverObj = new User();
    $driverObj->getUserWithId($rideObj->getDriverId());

    $passengerObj  = new User();
    $passengerObj->getUserWithId($rideObj->getPassengerId());


    if($userObj->getIsDriver()==1){
        // Cancelled By Driver.
        $cancel_time = $rideObj->getDateTimeDiffInMinutes($rideObj->getDriverArrivedAt(),$rideObj->getRideCancelledAt());
        if($rideObj->getIsDriverArrived()==1 &&
            $cancel_time >=DRIVER_CANCELLED_WAIT_AFTER_ARRIVED){
            $cancTransObj = Misc::generateCancelledTransaction($rideObj,$basePrice,$passengerObj,$driverObj);
//            $passengerObj->setBalance($passengerObj->getBalance()-$cancTransObj->getTotalFare());
//            $passengerObj->update();
        }else{
            $transObj = Misc::generateCancelledTransaction($rideObj,null);
            $userObj->setAcceptancePoints($userObj->getAcceptancePoints()-20);
            // TODO: Penalty to the Driver.
        }


        $fbaseObj = new firebaseNotification();
        $payload['message']="We are really very sorry! Ride Cancelled By the Driver! Please Book a new ride.";
        $payload['key']="p_ride_cancelled";
        $payload['user']=json_encode($passengerObj);
        $token = $passengerObj->getFirebaseToken();
        $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification,'high',30);

        // Set On Trip Status.
        $userObj->setIsDriverOnTrip(0);
        $userObj->update();

    }else{
        // cancelled by user:
        $meters = CooDistance::calculateDistanceBetweenTwoPoints($driverObj->getLat(),$driverObj->getLng(),$rideObj->getDriverLat(),$rideObj->getDriverLng(),'MT',true,5);

        // Add base fare to the User Balance in case of driver travelled 300 meters. otherwise create a transaction of the ride with 0 balance.
        $cancel_time = $rideObj->getDateTimeDiffInMinutes($rideObj->getCreatedAt(),$rideObj->getRideCancelledAt());
        if($cancel_time>=PASSENGER_CANCELLED_WAIT_AFTER_RIDE_REGISTER || $rideObj->getIsDriverArrived()==1){
            $transObj = Misc::generateCancelledTransaction($rideObj,$basePrice,$passengerObj,$driverObj);
        }else{
            $transObj = Misc::generateCancelledTransaction($rideObj,null);
        }
        $fbaseObj = new firebaseNotification();

        // Set On Trip Status.
        $driverObj->setIsDriverOnTrip(0);
        $driverObj->update();

        $payload['message']="Ride Cancelled By the Passenger";
        $payload['key']="d_ride_cancelled";
        $payload['user']=json_encode($driverObj);
        $token = $driverObj->getFirebaseToken();
        $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,'high',30);

    }



}else{
    $rideObj->setResponse("ride_cancel_error");
    $rideObj->setMessage("Unable to Cancel the ride.");
}
$rideObj->update();
$res = array("ride"=>$rideObj,"user"=>($userObj->getIsDriver()?$driverObj:$passengerObj),
    "response"=>$rideObj->getResponse(),"message"=>$rideObj->getMessage());
$var = json_encode($res);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




