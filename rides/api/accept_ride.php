<?php
require_once __DIR__."/../../model/LogRequest.php";
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/ride.php";
require_once __DIR__."/../../model/rideAlert.php";
require_once __DIR__."/../../utils/firebaseNotification.php";
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
$response = $rideObj->assignRideToDriver($_REQUEST['ride_id'],$driverObj->getId());
if($response=='driver_assigned'){
    $rideObj->setResponse("you_got_it");
    $rideObj->setMessage("Ride has been assigned to you");
}else{
    $rideObj->setResponse("some_one_else_got_it");
    $rideObj->setMessage("Sorry you are late.Someone Picked the Ride");
}
$var = json_encode($rideObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




