<?php
require_once __DIR__."/../../vendor/autoload.php";

$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['id']);
$lr->insertLog();

$token = $_REQUEST['token'];
$id = $_REQUEST['id'];
$smsDevicesToken = new SmsDevices();
$smsDevicesToken->updateDeviceTokenWithDeviceGroup($token,$id);
echo json_encode(array("status"=>"200"));
