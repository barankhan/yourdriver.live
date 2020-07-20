<?php
require_once __DIR__."/../../vendor/autoload.php";


$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->insertLog();
$firebaseLogObj = new FirebaseLogSMS();
$firebaseLogObj->setFirebaseMessageId($_REQUEST["firebase_message_id"]);
$firebaseLogObj->getByFirebaseId();
$firebaseLogObj->setFirebaseConfirmation(1);
$firebaseLogObj->setFirebaseConfirmedAt(date("Y-m-d H:i:s"));
$firebaseLogObj->update();

if($firebaseLogObj->getTableName()=='sms_queue'){
    if($firebaseLogObj->getTableId()>0) {
        $smsQueueObj = new SMSQueue();
        $smsQueueObj->setId($firebaseLogObj->getTableId());
        $smsQueueObj->findById();
        $smsQueueObj->setFirebaseRequestReceived(1);
        $smsQueueObj->updateSent();
    }

}else if($firebaseLogObj->getTableName()=='contacts'){
    if($firebaseLogObj->getTableId()>0){
        $contactObj = new Contacts();
        $contactObj->setId($firebaseLogObj->getTableId());
        $contactObj->findById();
        $contactObj->setFirebaseRequestReceived(1);
        $contactObj->update();
    }
}


echo json_encode(array("message"=>"done","response"=>"done"));




