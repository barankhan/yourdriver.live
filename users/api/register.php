<?php
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../utils/CurlPost.php";
require_once __DIR__."/../../model/LogRequest.php";
require_once __DIR__."/../../utils/sendSMS.php";


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
$userObj = new User();
$userObj->getUserWithMobile($mobile);
if($userObj->getId()==0){
    $userObj->setEmail($email);
    $userObj->setName($name);
    $userObj->setMobile($mobile);
    $userObj->setPassword($password);
    $userObj->setFirebaseToken($firebase_token);
    $userObj->setVerificationToken(mt_rand(10000,99999));
    $userObj->registerUser();
    if($userObj->getId()!=0) {
        $userObj->setResponse("inserted");
        $post = [
            'message' => "Registration Verification code for the Driver App is: ".$userObj->getVerificationToken(),
            'mobile_number' => $userObj->getMobile(),
        ];

        $sendSMSObj = new sendSMS();
        $res = $sendSMSObj->sendPayloadOnly($post);
        $lr->setResponseBody($res);
        $lr->updateResponse();
    }
    else{
        $userObj->setResponse("error");
    }

}else{
    $userObj->setResponse("exists");
}
header('Content-Type: application/json');
echo json_encode($userObj);
