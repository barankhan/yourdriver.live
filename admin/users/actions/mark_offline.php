<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/21/20
 * Time: 8:40 AM
 */
require_once __DIR__."/../../partials/validate_session.php";

$userObj = new User();
$userObj->getUserWithId($_REQUEST["id"]);
$userObj->getId();


$userObj->setIsDriverOnline(0);
$userObj->setLat(0);
$userObj->setLng(0);
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

header("Location: ".MY_HOST."/admin/users/online_drivers.php?msg=Marked offline to: ".$userObj->getName());

