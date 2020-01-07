<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/7/20
 * Time: 6:18 PM
 */
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['ride_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();


$driverObj = new User();
$driverObj->getUserWithId($_REQUEST['driver_id']);
$driverObj->setDriverRating($_REQUEST['rating']);
$driverObj->update();


