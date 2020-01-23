<?php
require_once __DIR__."/../../vendor/autoload.php";

$token = $_REQUEST['token'];
$smsDevicesToken = new SmsDevices();
$smsDevicesToken->updateDeviceToken($token);
echo json_encode(array("status"=>"200"));
