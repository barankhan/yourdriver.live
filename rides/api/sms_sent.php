<?php
require_once __DIR__."/../../vendor/autoload.php";
$id = $_REQUEST["contact_log_id"];
$contactLogObj = new ContactsLog();
$contactLogObj->setIsSent(1);
$contactLogObj->update();
echo json_encode(array("status"=>"ok"));




