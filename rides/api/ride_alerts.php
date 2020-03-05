<?php
require_once __DIR__."/../../vendor/autoload.php";


$mobile= $_REQUEST['driver_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();


$rideAlert =  new rideAlert();
$rideAlert->setDriverId($_REQUEST['driver_id']);
$alerts = $rideAlert->getDriverAlerts($_REQUEST['page_no'],20);

$response = array();

foreach ($alerts as $alert){

    $obj = new rideAlert();
    $obj->setAllFields($alert);
    $response[]=$obj;
}


echo json_encode($response);
