<?php
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();


$transObj = new DriverTransaction();
$transObj->setId($_REQUEST["trans_id"]);
$transObj->findById();
$transObj->setAmountReceived($_REQUEST['amount_received']);
$transObj->setTotalAmount();
if($transObj->update()){
    $transObj->setResponse("amount_update_success");
    $transObj->setMessage("Your Amount has been updated on the server.");

    $passengerObj = new User();
    $passengerObj->getUserWithId($transObj->getPassengerId());

    $notification['title']='Thanks for the Payment';
    $notification['body']='Driver has received :'.$transObj->getAmountReceived();
    $payload['do']="driver_arrived";
    $payload['msg']="Driver is reached at your pickup location!";
    $payload['key']="driver_arrived";

    $fbaseObj = new firebaseNotification();


    $token = $passengerObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,$notification,'high');




}else{
    $transObj->setResponse("amount_update_error");
    $transObj->setMessage("Unable to update the amount");
}




$var = json_encode($rideObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




