<?php
require_once __DIR__."/../../vendor/autoload.php";


$mobile= $_REQUEST['passenger_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();


$driverTransObj = new DriverTransaction();
$driverTransObj->setPassengerId($_REQUEST['passenger_id']);
$transactions = $driverTransObj->getDriverTransactions($_REQUEST['page_no'],20);

$response = array();

foreach ($transactions as $transaction){

    $obj = new DriverTransaction();
    $obj->setAllFields($transaction);
    $response[]=$obj;
}


echo json_encode($response);
