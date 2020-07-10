<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 6:10 PM
 */
require_once __DIR__."/../partials/validate_session.php";

$userObj = new User();
$userObj->getUserWithId($_REQUEST['id']);
$userObj->setIsDriver(1);
$userObj->update();

$smsDevicesObj = new SmsDevices();
$smsDevicesObj->getSMSSendingDevice(4);
if($smsDevicesObj->getId()>0) {
    $payload = [
        'message' => "App ka Driver App ka account approve ho giya hy. App training ky liye ye wali tamam videos deakhin please. https://yourdriver.live/stakeholder-traning/ or referral program ky liye https://yourdriver.live/referral/ or apni location her waqt update rakhnay ky liye Application ki setting karin: https://yourdriver.live/bo/",
        'mobile_number' => $userObj->getMobile(),
        'log_id' => "010"
    ];

    $obj = new firebaseNotificationSendSMS();
    $obj->sendPayLoadToSMSOnly($smsDevicesObj->getToken(), $payload);
}



$fbaseObj = new firebaseNotification();

$payload['message']="Congratulation! You're a stakeholder now, Please login.";
$payload['key']="driver_successful";
$token = $userObj->getFirebaseToken();

echo $fbaseObj->sendPayloadOnly(0,$token,$payload,null,'high',25000);