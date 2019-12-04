<?php
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../utils/CurlPost.php";
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

        $curr = new CurlPost('http://10.10.8.19/driver/sendMessagingService/api/sendSMSRequest.php');

        try {
            // execute the request
            $curl($post);
        } catch (RuntimeException $ex) {
            // catch errors
            die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
        }
    }
    else{
        $userObj->setResponse("error");
    }

}else{
    $userObj->setResponse("exists");
}
header('Content-Type: application/json');
echo json_encode($userObj);