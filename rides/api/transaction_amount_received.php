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
$transObj->setAmountReceivedAt(date("Y-m-d H:i:s"));
$transObj->setTotalAmount();

$driverObj = new User();
$driverObj->getUserWithId($transObj->getDriverId());

if($transObj->update()){
    $transObj->setResponse("amount_update_success");
    $transObj->setMessage("Your Amount has been updated on the server.");
    $passengerObj = new User();
    $passengerObj->getUserWithId($transObj->getPassengerId());
    $payload['message']='Driver has received :'.$transObj->getAmountReceived().', Your balance is:'.$passengerObj->getBalance();
    $payload['key']="p_amount_received";
    $fbaseObj = new firebaseNotification();
    $token = $passengerObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,'high');

}else{
    $transObj->setResponse("amount_update_error");
    $transObj->setMessage("Unable to update the amount");
}
$res = array("user"=>$driverObj,"transaction"=>$transObj);
$var = json_encode($res);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;




