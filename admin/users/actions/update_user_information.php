<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 3/21/20
 * Time: 5:20 PM
 */


require_once __DIR__."/../../partials/validate_session.php";



$userId= $_REQUEST['id'];
$userObj = new User();
$userObj->getUserWithId($userId);



if($userObj->getId()>0){
    $userObj->setVehicleType($_REQUEST['vehicle_type']);
    $userObj->setVehicleColor($_REQUEST['vehicle_color']);
    $userObj->setVehicleMade($_REQUEST['vehicle_made']);
    $userObj->update();
}

header("Location: ".MY_HOST."/admin/users/update_driver_information.php?id=".$userObj->getId());
