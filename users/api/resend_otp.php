<?php
require_once __DIR__ . "/../../vendor/autoload.php";


$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();


$userObj = new User();
$userObj->getUserWithMobile($_REQUEST['mobile']);
if($userObj->getId()!=0) {
    $userObj->setResponse("inserted");
    header('Content-Type: application/json');
    echo json_encode($userObj);
    fastcgi_finish_request();


    $post = [
        'message' => "OTP for the Driver App is: ".$userObj->getVerificationToken(),
        'mobile_number' => $userObj->getMobile(),
    ];

    $sendSMSObj = new firebaseNotificationSendSMS();
    $res = $sendSMSObj->sendPayloadOnly($lr->getId(),$post);
    
    $smsDevices = new SmsDevices();
    $smsDevices->getSMSSendingDevice(5);

    $sendSMSObj = new firebaseNotificationSendSMS();
    $res = $sendSMSObj->sendPayLoadToSMSOnly($smsDevices->getToken(),$post);

    $lr->setResponseBody(json_encode($res));
    $lr->updateResponse();
}
else{
    $userObj->setResponse("error");
    header('Content-Type: application/json');
    echo json_encode($userObj);
}


