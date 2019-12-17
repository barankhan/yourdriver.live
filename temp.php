<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/10/19
 * Time: 6:14 PM
 */
require_once __DIR__.'/vendor/autoload.php';

//$arr =  basePrice::getBasePrice("Auto","30.221891884597262","71.47176414728165");
//var_dump($arr);

//$fbaseLogObj  = new FirebaseLog();



$fbaseObj = new firebaseNotification();

$notification['title']='Ride Alert';
$notification['body']='Passenger is waiting for you.';
$notification['sound']="ride_alert.mp3";
$notification['channel_id']="121212";
$payload['do']="ride_alert";
$payload['key']="ride_alert";
$payload['msg']="You have a new ride";
$payload['lat']="30.222079903772556";
$payload['lng']="71.47192474454641";
$payload['phone']="ride_alert";
$payload['ride_id']="39";
$notification['click_action']='com.barankhan.driver.ride_alert_activity';



$payload['do']="ride_alert";
$payload['msg']="You have a new ride";
$payload['key']="ride_alert";
$payload['lat']="30.222079903772556";
$payload['lng']="71.47192474454641";
$payload['ride_id']="2";


//$fbaseLogObj->setNotification(json_encode($notification));
//$fbaseLogObj->setPayload(json_encode($payload));



$token = "cS5xp-yVVDg:APA91bFX7Mxh6fr_oPf-tf6PFry6Uaac2uvw1skWahSQhVFLq2hzaLOefycesjxmjeWo2SLJ--O9AK7VT7KqjeHk_gieDMvZMaBT1B3VsxRQtI-uulbkVADkuB_ouecI94bAfmcF-Pd0";
$_token="ecBOb_Cj7P8:APA91bEVQEI1iXNesk3X6xU6_qT_l7uy6lK_pNhJs9S_u3TKuaNjdxU0Btte6nS6E_LG6Jv__ZtM5MZpZC94LDJ5Sfb0Q8ZQeFFGY-ewMOx3aqmgiaDU-_XuGUJK70ZDvyoDPkvh0XF-";
$token = "cS5xp-yVVDg:APA91bFX7Mxh6fr_oPf-tf6PFry6Uaac2uvw1skWahSQhVFLq2hzaLOefycesjxmjeWo2SLJ--O9AK7VT7KqjeHk_gieDMvZMaBT1B3VsxRQtI-uulbkVADkuB_ouecI94bAfmcF-Pd0";
//$fbaseLogObj->setFirebaseKey($token);
//$fbaseLogObj->insert();




$fabseRes = $fbaseObj->sendPayloadOnly(1,$token,$payload,null);


var_dump($fabseRes);
echo "<hr/>";
echo $res_obj->results[0]->message_id;