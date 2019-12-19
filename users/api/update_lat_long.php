<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/6/19
 * Time: 1:38 PM
 */

require_once __DIR__."/../../vendor/autoload.php";

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


if($_REQUEST['passenger_id']!=''){
    $passObj = new User();
    $passObj->getUserWithId($_REQUEST['passenger_id']);
    $fbaseObj = new firebaseNotification();
    $payload['do']="driver_location_update";
    $payload['msg']="LocationUpdated";
    $payload['key']="driver_location_update";
    $payload['lat']=$_REQUEST['lat'];
    $payload['lng']=$_REQUEST['lng'];
    $token = $passObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,'normal');
    if($_REQUEST['ride_id']!=''){
        $rideObj = new ride();
        $rideObj->setId($_REQUEST['ride_id']);
        $rideObj->findRideWithId();
        if($rideObj->getIsRideStarted()==1){
            $ridePathObj = new RidePath();
            $ridePathObj->setRideId($rideObj->getId());
            $ridePathObj->setLat($_REQUEST['lat']);
            $ridePathObj->setLng($_REQUEST['lng']);
            $ridePathObj->insert();
        }
    }
}

