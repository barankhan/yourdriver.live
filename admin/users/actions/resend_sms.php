<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/11/20
 * Time: 1:19 PM
 */
require_once __DIR__."/../../partials/validate_session.php";

echo $id = $_REQUEST['id'];

$userObj = new User();
$userObj->getUserWithId($id);
$post = [
    'message' => "OTP for the Driver App is: ".$userObj->getVerificationToken(),
    'mobile_number' => $userObj->getMobile(),
];

$sendSMSObj = new firebaseNotificationSendSMS();
$res = $sendSMSObj->sendPayloadOnly(0,$post);

header("Location: ".MY_HOST."/admin/users/list_not_verified_users.php?msg=SMS Sent to ".$userObj->getName());



