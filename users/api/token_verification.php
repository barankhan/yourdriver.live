<?php
require_once __DIR__."/../../model/user.php";
$mobile = $_REQUEST['mobile'];
$token = $_REQUEST['verificationToken'];

$userObj = new User();
$userObj->getUserWithMobile($mobile);
if($userObj->validateRegistrationToken($token))
{
    $userObj->setResponse("verified");
    $userObj->setIsVerified(1);
}else{
    $userObj->setResponse("not-verified");
    $userObj->setIsVerified(0);
}



header('Content-Type: application/json');
echo json_encode($userObj);