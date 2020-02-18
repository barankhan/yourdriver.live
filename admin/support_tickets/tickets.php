<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/21/20
 * Time: 11:16 AM
 */
require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../partials/header.php";


$ticketsObj = new SupportTicket();
$tickets = $ticketsObj->getLatestPendingReplyOpenTickets();
echo '<ul class="list-group">';
foreach ($tickets as $ticket){
    $ticObj  = new SupportTicket();
    $ticObj->setAllFields($ticket);
    echo '<li class="list-group-item">';
    echo "<a href='ticket_details.php?id=".$ticObj->getId()."'>".$ticObj->getTitle()."</a>";

    echo "<span class='float-right'>".$ticObj->getUpdatedAt()."</span>";
}
echo "</ul>";