<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 3/13/20
 * Time: 2:57 PM
 */

require_once __DIR__ . "/../../vendor/autoload.php";

$userObj = new User();
$userObj->getUserWithId($_REQUEST['passenger_id']);

$rsReferredUsers = $userObj->getReferredUsers();


$response = array("Balance"=>$userObj->getBalance(),"ReferralCode"=>$userObj->getId(),
    "ReferredDrivers"=>$rsReferredUsers['driver_referred'],"ReferredUsers"=>$rsReferredUsers['passenger_referred']
);

echo json_encode($response);

