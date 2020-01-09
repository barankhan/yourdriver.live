<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/10/19
 * Time: 6:14 PM
 */
require_once __DIR__.'/vendor/autoload.php';

//$s = "Rs 5,100.00 have been deposited in your JazzCash Mobile Account from SADIQ ABAD. Pay only Rs 5,100.00 to Agent. Fee: Rs 0.00, Balance: Rs 5,100.00. TID: 007106280421.";
//$s_array = explode(" ",$s);
//echo str_replace(",",'',$s_array[1]) ;
//echo "<br/>";
//echo str_replace(".",'',$s_array[count($s_array)-1]);
//die;
//
//$res = preg_replace("/[^0-9.]/", "", $s);
//var_dump($res);



//echo $s = "Trx ID 5794455210. You have Received Rs. 200.00 from TMFB in your Easypaisa Account  Fee for this transaction is Rs. 0.00. Your new Easypaisa Account balance is Rs. 252.00";
//echo $s = "Trx ID 5943544618. You have successfully deposited Rs. 300.00 and your new easypaisa balance is 538.00. The fee for this transaction is Rs. 0.00.";
echo $s= "Trx ID 5944229251. You have Received Rs 10.00 from BARAN with Easypaisa Account 923336126632 and your new Easypaisa account balance is Rs. 10.49.";
echo "<br/>";
$s = strtolower($s);
echo $s = str_replace("rs ","rs. ",$s);

$s_array = explode(" ",$s);
echo "<br/>";
echo str_replace(".","",$s_array[2]);
echo "<br/>";

$rs_array = explode("rs. ",$s);
$rs_array = explode(" ",$rs_array[1]);

echo str_replace(",",'',$rs_array[0]) ;



//$arr =  basePrice::getBasePrice("Auto","30.221891884597262","71.47176414728165");
//var_dump($arr);

//$fbaseLogObj  = new FirebaseLog();

//
//
//$fbaseObj = new firebaseNotification();
//
//$notification['title']='Ride Alert';
//$notification['body']='Passenger is waiting for you.';
//$notification['sound']="ride_alert.mp3";
//$notification['channel_id']="121212";
//$payload['do']="teasing";
//$payload['key']="teasing";
//$payload['msg']="You have a new ride";
//$payload['lat']="30.222079903772556";
//$payload['lng']="71.47192474454641";
//$payload['phone']="ride_alert";
//$payload['ride_id']="39";
//$notification['click_action']='com.barankhan.driver.ride_alert_activity';
//
//
//
//
//$payload['key']="call_alert";
//$payload['message']="Calling....";
//$payload['agora_channel']="30.222079903772556";
//
//
//
////$fbaseLogObj->setNotification(json_encode($notification));
////$fbaseLogObj->setPayload(json_encode($payload));
//
//
//
//$token_ = "cS5xp-yVVDg:APA91bFX7Mxh6fr_oPf-tf6PFry6Uaac2uvw1skWahSQhVFLq2hzaLOefycesjxmjeWo2SLJ--O9AK7VT7KqjeHk_gieDMvZMaBT1B3VsxRQtI-uulbkVADkuB_ouecI94bAfmcF-Pd0";
//$token="ecBOb_Cj7P8:APA91bEVQEI1iXNesk3X6xU6_qT_l7uy6lK_pNhJs9S_u3TKuaNjdxU0Btte6nS6E_LG6Jv__ZtM5MZpZC94LDJ5Sfb0Q8ZQeFFGY-ewMOx3aqmgiaDU-_XuGUJK70ZDvyoDPkvh0XF-";
//$token_ = "cS5xp-yVVDg:APA91bFX7Mxh6fr_oPf-tf6PFry6Uaac2uvw1skWahSQhVFLq2hzaLOefycesjxmjeWo2SLJ--O9AK7VT7KqjeHk_gieDMvZMaBT1B3VsxRQtI-uulbkVADkuB_ouecI94bAfmcF-Pd0";
//$token_ = "fDqB3wnA1F8:APA91bGmzxjORhVk8tZdF0f0o7tXq_GK1FlFlRSaPaQvZXqZax-rTKZha9KWwgWEJuPxph79BSZP74leD9E_Mi-P-WBVuWvNQ2_7OeSSoCZ_EhC_lPu1xSdNQdmF5MGlBH2JNagJ8dXP";
//$token_ = "ecBOb_Cj7P8:APA91bEVQEI1iXNesk3X6xU6_qT_l7uy6lK_pNhJs9S_u3TKuaNjdxU0Btte6nS6E_LG6Jv__ZtM5MZpZC94LDJ5Sfb0Q8ZQeFFGY-ewMOx3aqmgiaDU-_XuGUJK70ZDvyoDPkvh0XF-";
//$token = "esk94lIo4Gc:APA91bHfHchHIv492-4hczbhVohIqLpgmNWT5GuQEasdmS3dnhuaXOR3hVe-5JIk0FAsDyl0oD8qX_ydEfJxc2gMgRaFtXhcJf9Xv2ZpF2rexPe8mLwtsyRapGglLaza8kyX2bZukaE-";
//$token = "c41h51hlpJc:APA91bGxeDW7vG7weanXEHYfS1rbUKHAi3nrxeqIw8w1x5NDTW3hAFFsyxD03w3wRn6tl39S47MbCO6xQXZqaBuDLEtlDwWafSAkSMj8GQoQsi9lQqeIkftpMVuj1ovijC3ZfO9_oSrH";
////$fbaseLogObj->setFirebaseKey($token);
////$fbaseLogObj->insert();
//
//
//
//
//$fabseRes = $fbaseObj->sendPayloadOnly(1,$token,$payload,null,'high');
//
//
//var_dump($fabseRes);
//echo "<hr/>";
//echo $res_obj->results[0]->message_id;