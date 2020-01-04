<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/4/20
 * Time: 1:02 PM
 */
require_once __DIR__."/../../vendor/autoload.php";
$ride_id = $_REQUEST['ride_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($ride_id);
$lr->insertLog();



$userID = $_REQUEST['user_id'];


$chatObj = new ChatHistory();
$chatObj->setRideId($_REQUEST['ride_id']);
$ride_chats = $chatObj->getRideChat();

$chatHistory = array();
foreach ($ride_chats as $chat){
    $cO = new ChatHistory();
    $cO->setAllFields($chat);
    $cO->setIsMe($userID);
    $chatHistory[]=$cO;
}

echo json_encode($chatHistory);