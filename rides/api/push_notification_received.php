<?php
require_once __DIR__."/../../vendor/autoload.php";


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

if($firebaseLogObj->getTableName()=='ride_alert'){
    $rideAlertObj = new rideAlert();
    $rideAlertObj->setId($firebaseLogObj->getTableId());
    $rideAlertObj->findAlertById();
    $rideAlertObj->setFirebaseRequestReceived(1);
    $rideAlertObj->update();
}else if($firebaseLogObj->getTableName()=='marked_offline'){
    if($firebaseLogObj->getTableId()>0){
        $markedOfflineObj = new MarkedOffline();
        $markedOfflineObj->setId($firebaseLogObj->getTableId());
        $markedOfflineObj->findById();
        $markedOfflineObj->setFirebaseRequestReceived(1);
        $markedOfflineObj->update();
    }
}


echo json_encode(array("message"=>"done","response"=>"done"));




