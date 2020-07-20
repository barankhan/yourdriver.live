<?php
require_once __DIR__."/../../vendor/autoload.php";

$token = $_REQUEST['token'];
$id = $_REQUEST['id'];
$smsDevicesToken = new SmsDevices();
$smsDevicesToken->updateDeviceTokenWithDeviceGroup($token,$id);
echo json_encode(array("status"=>"200"));
