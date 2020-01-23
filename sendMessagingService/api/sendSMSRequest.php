<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 11/26/19
 * Time: 10:22 AM
 */
require_once __DIR__."/../../vendor/autoload.php";


$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->insertLog();

$sms_devices_obj = new SmsDevices();
$dev = $sms_devices_obj->getCurrentSMSSedingDevice();

$message = $_REQUEST['message'];
$mob = $_REQUEST['mobile_number'];

$obj = new firebaseNotificationSendSMS();
$obj->sendPayLoadOnly($dev['token'],$payload,null,'high',60);


$lr->setResponseBody($result);
$lr->updateResponse();