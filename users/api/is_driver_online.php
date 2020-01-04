<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/6/19
 * Time: 1:38 PM
 */

require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/LogRequest.php";
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();


$flag = $_REQUEST['is_driver_online'];
$userObj = new User();
$userObj->getUserWithMobile($_REQUEST['mobile']);
$userObj->setIsDriverOnline($flag);
$userObj->setFirebaseToken($_REQUEST['firebaseToken']);

if($userObj->getIsDriverOnline()==0){
    $userObj->setLat(0);
    $userObj->setLng(0);
}

if($userObj->update())
{
    $arr  =    array("response"=>"success","message"=>($flag==1?"Online":"Offline"));
}else{
    $arr  =    array("response"=>"error","message"=>"Sorry you can't Change your status now.");
}
header('Content-Type: application/json');
$lr->setResponseBody(json_encode($arr));
$lr->updateResponse();
echo json_encode($arr);