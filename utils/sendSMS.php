<?php
require_once __DIR__ . "/../model/SmsDevices.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 6:15 PM
 */
class sendSMS
{

     private $firebaseAPIKey ="AIzaSyChw9Prigf-JNQmQoyligFeVQZR3Wvbovk";
     private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';



     public function sendPayloadOnly($payload=null,$notification=null){
         $obj = new SmsDevices();
         $device = $obj->getCurrentSMSSedingDevice();
         $fcmNotification['to'] = $device['token'];
         if(!empty($notification)){
             $fcmNotification['notification'] = $notification;
         }
         if(!empty($payload)){
             $fcmNotification['data'] = $payload;
         }
         $fcmNotification["priority"]='high';
         $headers = [
             'Authorization: key=' . $this->firebaseAPIKey,
             'Content-Type: application/json'
         ];
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$this->fcmUrl);
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
         $result = curl_exec($ch);
         curl_close($ch);
         return $result;
    }

}