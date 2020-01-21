<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/21/20
 * Time: 8:41 AM
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
$supportTicketObj->setId($_REQUEST['ticket_id']);
$supportTicketObj->getSupportTicketById();
$supportTicketObj->setIsUnread(0);
$supportTicketObj->update();

$supportTicketMessagesObj = new SupportTicketHistory();
$supportTicketMessagesObj->setSupportTicketId($_REQUEST['ticket_id']);
$ticket_histories = $supportTicketMessagesObj->getSupportTicketHistory();
$res = array();
foreach($ticket_histories as $history){
    $obj = new SupportTicketHistory();
    $obj->setAllFields($history);
    $obj->setIsMe($user_id);
    $res[]=$obj;
}

echo json_encode($res);

