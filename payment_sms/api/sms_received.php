<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/9/20
 * Time: 2:30 PM
 */
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['sender'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();

$paySmsObj = new PaymentSMS($_REQUEST["raw_sms"],$_REQUEST['sender']);
$paySmsObj->insert();
echo json_encode(array("message"=>"ok","response"=>"ok"));



