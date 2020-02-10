<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 6:15 PM
 */
class firebaseNotificationSendSMS
{
    public function sendPayLoadOnly($logId,$payload)
    {

        $sms_devices_obj = new SmsDevices();
        $dev = $sms_devices_obj->getCurrentSMSSedingDevice();


        $fbaseLogObj = new FirebaseLog();
        $fbaseLogObj->setPayload(json_encode($payload));
        $fbaseLogObj->setRequestLogId($logId);
        $fbaseLogObj->setFirebaseKey($dev['token']);


        $factory = (new Factory())
            ->withServiceAccount(__DIR__ . '/sendregistrationsms_firebasekey.json');
//        $notification = Notification::fromArray($notification);


        $config = AndroidConfig::fromArray([
            'ttl' => "60s",
            'priority' => 'high',
            'data'=>$payload
        ]);


        $messaging = $factory->createMessaging();
        $message = CloudMessage::withTarget('token', $dev['token']);
        $message = $message->withAndroidConfig($config);


        $result = $messaging->send($message);
        $res = explode("/", $result["name"]);
        $fbaseLogObj->setFirebaseResponse(json_encode($result));
        $fbaseLogObj->setFirebaseMessageId($res[3]);
        $fbaseLogObj->insert();



        return $result;
    }



    public function sendPayLoadToSMSOnly($device_token,$payload)
    {


        $factory = (new Factory())
            ->withServiceAccount(__DIR__ . '/sendregistrationsms_firebasekey.json');
//        $notification = Notification::fromArray($notification);


        $config = AndroidConfig::fromArray([
            'ttl' => "120s",
            'priority' => 'high',
            'data'=>$payload
        ]);


        $messaging = $factory->createMessaging();
        $message = CloudMessage::withTarget('token', $device_token);
        $message = $message->withAndroidConfig($config);


        $result = $messaging->send($message);
        $res = explode("/", $result["name"]);

        return $result;
    }


}




//class firebaseNotification
//{
//
//     private $firebaseAPIKey ="AIzaSyDTzHTWNjCuve-ynDUDpZXrv8TCIbwzTtc";
//     private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
//
//     public function sendPayloadOnly($logId,$token,$payload=null,$notification=null,$priority='normal'){
//         $fbaseLogObj  = new FirebaseLog();
//         $fbaseLogObj->setNotification(json_encode($notification));
//         $fbaseLogObj->setPayload(json_encode($payload));
//         $fbaseLogObj->setRequestLogId($logId);
//         $fbaseLogObj->setFirebaseKey($token);
//
//
//
//
//
//         $fcmNotification['to'] =  $token;
//         $fcmNotification['priority'] =  $priority;
//         if(!empty($notification)){
//             $fcmNotification['notification'] = $notification;
//         }
//         if(!empty($payload)){
//             $fcmNotification['data'] = $payload;
//         }
//         $headers = [
//             'Authorization: key=' . $this->firebaseAPIKey,
//             'Content-Type: application/json'
//         ];
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL,$this->fcmUrl);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
//         $result = curl_exec($ch);
//         curl_close($ch);
//
//         $res_obj = json_decode($result);
//         $fbaseLogObj->setFirebaseResponse($result);
//         $fbaseLogObj->setFirebaseMessageId($res_obj->results[0]->message_id);
//         $fbaseLogObj->insert();
//
//         return $result;
//    }
//
//}