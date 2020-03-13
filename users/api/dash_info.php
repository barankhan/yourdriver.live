<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 3/13/20
 * Time: 2:57 PM
 */

require_once __DIR__ . "/../../vendor/autoload.php";

$userObj = new User();
$userObj->getUserWithId($_REQUEST['driver_id']);

$rsReferredUsers = $userObj->getReferredUsers();

$transactionObj = new DriverTransaction();
$transactionObj->setDriverId($userObj->getId());
$transactionObj->getLastDaysEarning(1);

$response = array("Balance"=>$userObj->getBalance(),"ReferralCode"=>$userObj->getId(),
    "ReferredDrivers"=>$rsReferredUsers['driver_referred'],"ReferredUsers"=>$rsReferredUsers['passenger_referred'],
    "DashTodayEarning"=>$transactionObj->getLastDaysEarning(1),"Last7DaysEarning"=>$transactionObj->getLastDaysEarning(7),
    "Last30DaysEarning"=>$transactionObj->getLastDaysEarning(30)
);

echo json_encode($response);

