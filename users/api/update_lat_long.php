<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/6/19
 * Time: 1:38 PM
 */

require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/LogRequest.php";
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();

$userObj = new User();
$userObj->getUserWithMobile($_REQUEST['mobile']);
$userObj->setLat($_REQUEST['lat']);
$userObj->setLng($_REQUEST['lng']);
$userObj->update();

