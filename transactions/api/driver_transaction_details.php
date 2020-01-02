<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/2/20
 * Time: 4:14 PM
 */
require_once __DIR__."/../../vendor/autoload.php";


$mobile= $_REQUEST['trans_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();
$driverTransObj = new DriverTransaction();
$driverTransObj->setId($_REQUEST['trans_id']);
$driverTransObj->findById();
$passengerObj = new User();
$passengerObj->getUserWithId($driverTransObj->getPassengerId());
$rideObj = new ride();
$rideObj->setId($driverTransObj->getRideId());
$rideObj->findRideWithId();
$res = array("user"=>$passengerObj,"ride"=>$rideObj,"driver_transaction"=>$driverTransObj);
echo json_encode($res);





