<?php
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../utils/CurlPost.php";

$mobile = $_REQUEST['mobile'];
$userObj = new User();
$userObj->getUserWithMobile($mobile);
if($userObj->getId()!=0){
    $arr  =    array("response"=>"sms_sent","message"=>"We have sent an SMS to your mobile number");
    $post = [
        'message' => "Your password for the Driver App is: ".$userObj->getPassword(),
        'mobile_number' => $userObj->getMobile(),
    ];

    $curr = new CurlPost('http://10.10.8.19/driver/sendMessagingService/api/sendSMSRequest.php');

    try {
        // execute the request
        $curr($post);
    } catch (RuntimeException $ex) {
        // catch errors
        die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
    }



}else{
    $arr  =    array("response"=>"mobile_number_not_found","message"=>"You are not register with us");
}


header('Content-Type: application/json');
echo json_encode($arr);