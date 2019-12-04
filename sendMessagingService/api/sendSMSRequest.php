<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 11/26/19
 * Time: 10:22 AM
 */
require_once __DIR__."/../../model/sms_devices.php";

$sms_devices_obj = new SmsDevices();
$dev = $sms_devices_obj->getCurrentSMSSedingDevice();

$message = $_REQUEST['message'];
$mob = $_REQUEST['mobile_number'];

define('API_ACCESS_KEY',"AIzaSyChw9Prigf-JNQmQoyligFeVQZR3Wvbovk");
 $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
 $token=$dev["token"];
        $extraNotificationData = ["body" => $message,'mobile_number' =>$mob];
        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'data' => $extraNotificationData
        ];
        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;

