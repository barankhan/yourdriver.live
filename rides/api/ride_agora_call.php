<?php
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['from_mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();
$fromUserObj = new User();
$fromUserObj->getUserWithMobile($mobile);

$toUserObj = new User();
$toUserObj->getUserWithMobile($_REQUEST['to_mobile']);
$callObj = new CallHistory();
$callObj->setRideId($_REQUEST['ride_id']);
$callObj->setFromUserId($fromUserObj->getId());
$callObj->setToUserId($toUserObj->getId());
$callObj->setChannel("ride_call_".$_REQUEST['ride_id']);
$callObj->insert();
$var = json_encode(array("message"=>"Calling to ".$toUserObj->getName(),"response"=>"calling"));
echo $var;
fastcgi_finish_request();


$fbaseObj = new firebaseNotification();
$payload['agora_channel']=$callObj->getChannel();
$payload['ride_id']="".$_REQUEST['ride_id'];
$payload['key']="call_alert";
$payload['message']="Calling....";
$token = $toUserObj->getFirebaseToken();
$fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,'high');

$lr->setResponseBody($var);
$lr->updateResponse();





