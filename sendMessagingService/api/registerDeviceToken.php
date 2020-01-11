<?php
require_once "../../api/config.php";

$token = $_REQUEST['token'];

$q = "UPDATE `sms_devices` SET `token`=:token WHERE `id`='1';";
$sth = $pdo_conn->prepare($q);
$sth->bindParam(':token', $token);
$sth->execute();
echo json_encode(array("status"=>"200"));
