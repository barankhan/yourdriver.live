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

$res_array = array();

$response = $rideObj->assignRideToDriver($_REQUEST['ride_id'],$driverObj->getId(),$_REQUEST['driver_lat'],$_REQUEST['driver_lng']);
if($response=='driver_assigned'){
    $rideObj->setResponse("you_got_it");
    $rideObj->setMessage("Ride has been assigned to you");


    $passengerObj = new User();
    $passengerObj->getUserWithId($rideObj->getPassengerId());



    $res_array["response"]="you_got_it";
    $res_array["message"]="Ride has been assigned to you";
    $res_array["user"]=$passengerObj;
    $res_array["ride"]=$rideObj;

    $var = json_encode($res_array);
    echo $var;
    //fastcgi_finish_request();

    $fbaseObj = new firebaseNotification();
    $payload['message']="Driver is coming at your pickup location.";
    $payload['key']="p_ride_accepted";
    $payload['driver']=json_encode($driverObj);
    $payload['ride']=json_encode($rideObj);
    $token = $passengerObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,"high",30);



}else{
    $res_array["response"]="some_one_else_got_it";
    $res_array["message"]="Sorry you are late. Someone Picked the Ride";
    $var = json_encode($res_array);
    echo $var;
}

$lr->setResponseBody($var);
$lr->updateResponse();





