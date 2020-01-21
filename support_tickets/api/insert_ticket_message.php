<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/21/20
 * Time: 9:09 AM
 */

require_once __DIR__."/../../vendor/autoload.php";

$user_id= $_REQUEST['user_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($user_id);
$lr->insertLog();


$supportTicketMessagesObj = new SupportTicketHistory();
$supportTicketMessagesObj->setSupportTicketId($_REQUEST['ticket_id']);
$supportTicketMessagesObj->setUserId($_REQUEST['user_id']);
$supportTicketMessagesObj->setMessage($_REQUEST['message']);
$supportTicketMessagesObj->insert();


$supportTicketObj = new SupportTicket();
$supportTicketObj->setMessageCount($supportTicketObj->getMessageCount()+1);
$supportTicketObj->update();





echo json_encode(array("message"=>"Your ticket updated successfully!","response"=>"Ok"));





