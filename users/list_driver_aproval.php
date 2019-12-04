<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 5:53 PM
 */
require_once __DIR__."/../model/user.php";

$userObj = new User();
$drivers  = $userObj->getAllApprovalRequiredDrivers();
echo "<table>";
foreach ($drivers as $driver){
    $userObj->setAllFields($driver);
    echo "<tr>";
    echo "<td>".$userObj->getName()."</td>";
    echo "<td>".$userObj->getFather()."</td>";
    echo "<td>".$userObj->getCnic()."</td>";
    echo "<td>".$userObj->getRegAlphabet()."-".$userObj->getRegYear()."-".$userObj->getRegNo()."</td>";
    echo "<td><a href='approve_driver.php?id=".$userObj->getId()."'>Approve</a></td>";
    echo "</tr>";
}
echo "</table>";