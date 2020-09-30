<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 9/30/20
 * Time: 10:40 AM
 */


require_once __DIR__."/../vendor/autoload.php";


$userObjSearch = new User();
$users = $userObjSearch->getOnlineDrivers();




foreach($users as $rs){
    $userObj = new User();
    $userObj->setAllFields($rs);
    $userObj->setIsDriverOnline(0);
    $userObj->setIsDriverOnTrip(0);
    $userObj->setLat(0);
    $userObj->setLng(0);
    $userObj->setOnlineAt(null);
    $userObj->setOfflineAt(null);
    $userObj->update();


    $markedOfflineObj = new MarkedOffline();
    $markedOfflineObj->setDriverId($userObj->getId());
    $markedOfflineObj->insert();


    $fbaseObj = new firebaseNotification();
    $payload['message']="سسٹم نے  آپ کو  آف لائن کر دیا ہے . آپ دوبارہ آن لائن آ جائیں  شکریہ";
    $payload['key']="p_ride_cancelled";
    $payload['user']=json_encode($userObj);
    $token = $userObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly(0,$token,$payload,$notification,'high',43200,"marked_offline",$markedOfflineObj->getId());

}


