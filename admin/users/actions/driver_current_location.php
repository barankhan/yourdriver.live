<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/11/20
 * Time: 4:00 PM
 */
require_once __DIR__."/../../partials/validate_session.php";

$userObj = new User();
$userObj->getUserWithId($_REQUEST["id"]);
echo $userObj->getId();
header("Location: https://www.google.com/maps/search/?api=1&query=".$userObj->getLat().",".$userObj->getLng());