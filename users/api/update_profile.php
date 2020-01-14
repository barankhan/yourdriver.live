<?php
require_once __DIR__."/../../vendor/autoload.php";


$mobile = $_REQUEST['mobile'];
$name = $_REQUEST['name'];
$father = $_REQUEST['father'];
$email = $_REQUEST['email'];

$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();
$userObj = new User();
$userObj->getUserWithMobile($mobile);
$userObj->setName($name);
$userObj->setEmail($email);
$userObj->setFather($father);
$userObj->update();
header('Content-Type: application/json');
$lr->setResponseBody(json_encode($userObj));
$lr->updateResponse();
echo json_encode($userObj);