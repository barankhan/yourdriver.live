<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/3/20
 * Time: 4:17 PM
 */


require_once __DIR__."/../../vendor/autoload.php";

$driver_id= $_REQUEST['driver_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($driver_id);
$lr->insertLog();

$rideObj = new rideAlert();
$rideObj->setRideId($_REQUEST['ride_id']);
$rideObj->setDriverId($_REQUEST['driver_id']);
$rideObj->findAlertByDriverId();

$rideObj->setIsRejected(1);
$rideObj->setRejectedAt(date("Y-m-d H:i:s"));
$rideObj->update();

$a = array("message"=>123456,"response"=>"Ride Rejected Bossed");
echo json_encode($a);
