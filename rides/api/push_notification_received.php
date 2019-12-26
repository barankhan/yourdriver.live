<?php
require_once __DIR__."/../../model/LogRequest.php";
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/ride.php";
require_once __DIR__."/../../model/rideAlert.php";
require_once __DIR__."/../../utils/firebaseNotification.php";

$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->insertLog();
$firebaseLogObj = new FirebaseLog();
$firebaseLogObj->setFirebaseMessageId($_REQUEST["firebase_message_id"]);
$firebaseLogObj->getByFirebaseId();
$firebaseLogObj->setFirebaseConfirmation(1);
$firebaseLogObj->setFirebaseConfirmedAt(date("Y-m-d H:i:s"));
$firebaseLogObj->update();
echo json_encode(array("message"=>"done","response"=>"done"));




