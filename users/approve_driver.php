<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 6:10 PM
 */
require_once __DIR__."/../model/user.php";

$userObj = new User();
$userObj->getUserWithId($_REQUEST['id']);
$userObj->setIsDriver(1);
