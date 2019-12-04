<?php
require_once __DIR__."/../../model/user.php";
$mobile = $_REQUEST['mobile'];
$password = $_REQUEST['password'];
$firbaseToken = $_REQUEST['firebaseToken'];

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
echo json_encode($userObj);