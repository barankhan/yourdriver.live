<?php
require_once __DIR__ . "/../../vendor/autoload.php";


$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();


$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$password = $_REQUEST['password'];
$mobile = $_REQUEST['mobile'];
$firebase_token = $_REQUEST['firebaseToken'];
$referral_code = $_REQUEST['ref_code'];
$userObj = new User();
$userObj->getUserWithMobile($mobile);
if($userObj->getId()==0){
    $userObj->setEmail($email);
    $userObj->setName($name);
    $userObj->setMobile($mobile);
    $userObj->setPassword($password);
    $userObj->setFirebaseToken($firebase_token);
    $userObj->setReferralCode($referral_code);
    $userObj->setVerificationToken(mt_rand(10000,99999));
    $userObj->registerUser();
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
        $lr->setResponseBody(json_encode($res));
        $lr->updateResponse();
    }
    else{
        $userObj->setResponse("error");
        header('Content-Type: application/json');
        echo json_encode($userObj);
    }

}else{
    $userObj->setResponse("exists");
    header('Content-Type: application/json');
    echo json_encode($userObj);
}

