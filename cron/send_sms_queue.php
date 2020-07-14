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
    if ($contactsLogObj->getTodayCountOfSender() < 2000 && $contactsLogObj->getLast15MinutesCountOfSender() < 200) {
        $smsQueueObj = new SMSQueue();
        $smsQueueObj->setSendBy($sender_id);
        $smsQueueObj->getNumberToSendSMS();
        if ($smsQueueObj->getId() > 0) {
            $smsQueueObj->setIsSent(1);
            $smsQueueObj->setSentAt(date('Y-m-d H:i:s'));
            $smsQueueObj->updateSent();
            $contactsLogObj->setSentBy($sender_id);
            $contactsLogObj->setContactId($smsQueueObj->getId());
            $contactsLogObj->setType("SMS_QUEUE");
            $contactsLogObj->insert();

            $payload = [
                'message'=>$smsQueueObj->getMessage(),
                'mobile_number' => $smsQueueObj->getNumber(),
                'sim'=>"".$smsDevicesObj->getSimSlot(),
                'log_id' => "" . $contactsLogObj->getId()
            ];

            $obj = new firebaseNotificationSendSMS();
            $ab = $obj->sendPayLoadToSMSOnly($smsDevicesObj->getToken(), $payload);

        }
    }

}else{

}

