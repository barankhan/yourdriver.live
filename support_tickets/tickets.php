<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/21/20
 * Time: 11:16 AM
 */
require_once __DIR__."/../vendor/autoload.php";


$ticketsObj = new SupportTicket();
$tickets = $ticketsObj->getLatestPendingReplyOpenTickets();

foreach ($tickets as $ticket){
    $ticObj  = new SupportTicket();
    $ticObj->setAllFields($ticket);
    echo "<a href='ticket_details.php?id=".$ticObj->getId()."'>".$ticObj->getTitle()."</a>";
    echo "<br/>";
}