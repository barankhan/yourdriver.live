<?php
require_once __DIR__."/../../vendor/autoload.php";


$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();


$mobile = $_REQUEST['mobile'];
$userObj = new User();
$userObj->getUserWithMobile($mobile);
if($userObj->getId()!=0){
    $arr  =    array("response"=>"sms_sent","message"=>"We have sent an SMS to your mobile number");

//    header('Content-Type: application/json');
    echo json_encode($arr);
//    fastcgi_finish_request();


    $post = [
        'message' => "Your password for the Driver App is: ".$userObj->getPassword(),
        'mobile_number' => "".$userObj->getMobile(),
    ];

    $sendSMSObj = new firebaseNotificationSendSMS();
    $res = $sendSMSObj->sendPayloadOnly($lr->getId(),$post);


    $lr->setResponseBody(json_encode($res));
    $response = $lr->updateResponse();






}else{
    $arr  =    array("response"=>"mobile_number_not_found","message"=>"You are not register with us");
    header('Content-Type: application/json');
    echo json_encode($arr);
}


$lr->setResponseBody(json_encode($arr).json_encode($response));
