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
$supportTicketObj->setTitle($_REQUEST['title']);
$supportTicketObj->insert();

$ticketHistoryObj = new SupportTicketHistory();
$ticketHistoryObj->setSupportTicketId($supportTicketObj->getId());
$ticketHistoryObj->setMessage(trim($_REQUEST['message']));
$ticketHistoryObj->setUserId($user_id);
$ticketHistoryObj->insert();

echo json_encode(array("response"=>'done','message'=>'Your Ticket has been generated.'));
