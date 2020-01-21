<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/20/20
 * Time: 12:58 PM
 */

require_once __DIR__."/../../vendor/autoload.php";

$user_id= $_REQUEST['user_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($user_id);
$lr->insertLog();


$supportTicketObj = new SupportTicket();
$supportTicketObj->setUserId($user_id);
$tickets  = $supportTicketObj->getUserSupportTickets($_REQUEST['page_no']);


$response = array();
foreach ($tickets as $ticket){
    $obj = new SupportTicket();
    $obj->setAllFields($ticket);
    $response[]=$obj;
}
echo json_encode($response);
