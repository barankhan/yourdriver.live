<?php
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/LogRequest.php";


$mobile = $_REQUEST['mobile'];
$password = $_REQUEST['password'];
$firbaseToken = $_REQUEST['firebaseToken'];

$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();


$userObj = new User();
$userObj->getUserWithMobileAndPassword($mobile,$password);
if($userObj->getId()!=0)
{
    $userObj->updateFirebaseToken($firbaseToken);
    $userObj->setResponse("data");
}else{
    $userObj->setResponse("login_failed");
}

header('Content-Type: application/json');
$lr->setResponseBody(json_encode($userObj));
$lr->updateResponse();
echo json_encode($userObj);