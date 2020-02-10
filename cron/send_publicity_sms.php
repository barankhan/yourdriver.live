<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/10/20
 * Time: 2:56 PM
 */

require_once __DIR__."/../vendor/autoload.php";

$sender_id = $argv[1];

$smsDevicesObj = new SmsDevices();
$smsDevicesObj->getSMSSendingDevice($sender_id);

$contactsLogObj = new ContactsLog();


if($smsDevicesObj->getId()>0){
    echo "I found sms device\n";
    $contactsLogObj->setSentBy($sender_id);
    if($contactsLogObj->getTodayCountOfSender()<2000){
        $contactsObj = new Contacts();
        $contactsObj->setSentBy($sender_id);
        $contactsObj->getNumberToSendSMS();
        if($contactsObj->getId()>0){
            $contactsObj->setSentBy($sender_id);
            $contactsObj->setSentCount($contactsObj->getSentCount()+1);
            $contactsObj->update();
            $contactsLogObj->setSentBy($sender_id);
            $contactsLogObj->setContactId($contactsObj->getId());
            $contactsLogObj->insert();

            $payload = [
                'message' => "Tired of Heavy fares? Download App https://yourdriver.live/download.php",
                'mobile_number' => $contactsObj->getContactNo(),
                'log_id'=>$contactsLogObj->getId()
            ];

            $obj = new firebaseNotificationSendSMS();
            $obj->sendPayLoadToSMSOnly($smsDevicesObj->getToken(),$payload);


        }
    }



}else{
    echo "no device found\n";
}

