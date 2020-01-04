<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/4/20
 * Time: 11:36 AM
 */
require_once __DIR__."/../../vendor/autoload.php";
$ride_id = $_REQUEST['ride_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($ride_id);
$lr->insertLog();


$rideObj  = new ride();
$rideObj->setId($_REQUEST['ride_id']);
$rideObj->findRideWithId();

$userObj = new User();

if($_REQUEST['sender_id']==$rideObj->getPassengerId()){
    //send push notification to driver.
    $userObj->getUserWithId($rideObj->getDriverId());

}else{
    //send push notification to passenger.
    $userObj->getUserWithId($rideObj->getPassengerId());
}


$chatObj = new ChatHistory();
$chatObj->setSenderId($_REQUEST['sender_id']);
$chatObj->setMessage($_REQUEST['message']);
$chatObj->setRideId($_REQUEST['ride_id']);
$chatObj->insert();

$notification['title'] = 'New Message Received';
$notification['body'] = $chatObj->getMessage();
$notification['click_action']="com.barankhan.driver.chat";

$payload['message'] = $chatObj->getMessage();
$payload['key'] = "chat_message_received";
$payload['ride_id'] = "".$chatObj->getRideId();

$fbaseObj = new firebaseNotification();
$token = $userObj->getFirebaseToken();
$fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(), $token, $payload, $notification, 'high',60);


$res = array("message"=>"message sent successfully","response"=>"message_sent");

echo json_encode($res);



