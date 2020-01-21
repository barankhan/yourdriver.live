<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/21/20
 * Time: 11:16 AM
 */
require_once __DIR__."/../../vendor/autoload.php";


$ticketsObj = new SupportTicket();
$ticketsObj->getLatestTickets();