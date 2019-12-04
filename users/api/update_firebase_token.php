<?php
require_once __DIR__."/../../model/user.php";
$mobile = $_REQUEST['mobile'];
$token = $_REQUEST['firebaseToken'];

$userObj = new User();
$userObj->getUserWithMobile($mobile);
if($userObj->getId()!=0){
        $userObj->updateFirebaseToken($token);
}
header('Content-Type: application/json');
echo json_encode($userObj);