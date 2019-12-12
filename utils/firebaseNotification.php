<?php
require_once __DIR__."/../model/FirebaseLog.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 6:15 PM
 */
class firebaseNotification
{

     private $firebaseAPIKey ="AIzaSyDTzHTWNjCuve-ynDUDpZXrv8TCIbwzTtc";
     private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

     public function sendPayloadOnly($logId,$token,$payload=null,$notification=null){
         $fbaseLogObj  = new FirebaseLog();
         $fbaseLogObj->setNotification(json_encode($notification));
         $fbaseLogObj->setPayload(json_encode($payload));
         $fbaseLogObj->setRequestLogId($logId);
         $fbaseLogObj->setFirebaseKey($token);





         $fcmNotification['to'] =  $token;
         if(!empty($notification)){
             $fcmNotification['notification'] = $notification;
         }
         if(!empty($payload)){
             $fcmNotification['data'] = $payload;
         }
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

         $res_obj = json_decode($result);
         $fbaseLogObj->setFirebaseResponse($result);
         $fbaseLogObj->setFirebaseMessageId($res_obj->results[0]->message_id);
         $fbaseLogObj->insert();

         return $result;
    }

}