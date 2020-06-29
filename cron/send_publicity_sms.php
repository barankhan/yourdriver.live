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


if($smsDevicesObj->getId()>0) {

    $contactsLogObj->setSentBy($sender_id);
    if ($contactsLogObj->getTodayCountOfSender() < 2900 && $contactsLogObj->getLast15MinutesCountOfSender() < 200) {
        $contactsObj = new Contacts();
        $contactsObj->setSentBy($sender_id);
        $contactsObj->getNumberToSendSMS(1);
        if ($contactsObj->getId() > 0) {
            $contactsObj->setSentBy($sender_id);
            $contactsObj->setSentCount($contactsObj->getSentCount() + 1);
            $contactsObj->update();
            $contactsLogObj->setSentBy($sender_id);
            $contactsLogObj->setContactId($contactsObj->getId());
            $contactsLogObj->insert();


            if ($contactsObj->getSentCount() == 1) {
                $payload = [
//                    'message' => "آٹو رکشہ نہایت سستے کرایہ پر بک کریں  https://yourdriver.live/download.php",
                    'message'=>"Bahawalpur walo, find smart rides solution @ https://yourdriver.live/registration/",
                    'mobile_number' => $contactsObj->getContactNo(),
                    'log_id' => "" . $contactsLogObj->getId()
                ];
            } else {
                $payload = [
                    'message' => "Bahawalpur walo, find smart rides solution @ https://yourdriver.live/registration/",
                    'mobile_number' => $contactsObj->getContactNo(),
                    'log_id' => "" . $contactsLogObj->getId()
                ];
            }


            $obj = new firebaseNotificationSendSMS();
            $obj->sendPayLoadToSMSOnly($smsDevicesObj->getToken(), $payload);


        }
    }

}else{

}

