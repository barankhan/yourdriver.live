<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/30/20
 * Time: 6:05 PM
 */
require_once __DIR__ . "/../partials/header.php";

$userObj = new User();
$drivers = $userObj->getUserWithId($_REQUEST['id']);


?>

<h2>ID: <?php echo $userObj->getId(); ?>, Mobile: <?php echo $userObj->getMobile(); ?>
    (<?php echo $userObj->getVehicleType(); ?>)</h2>

<a class="btn btn-primary" href="update_driver_information.php?id=<?php echo $userObj->getId(); ?>" target="_blank">Update Information</a>
<table class="table table-striped">
    <tr>
        <th class="w-15">Name:</th>
        <td class="w-35"><?php echo $userObj->getName(); ?></td>
        <th class="w-15">Father:</th>
        <td class="w-35"><?php echo $userObj->getFather(); ?> </td>
    </tr>
    <tr>
        <th class="w-15"> CNIC:</th>
        <td class="w-35"><?php echo $userObj->getCnic(); ?></td>
        <th class="w-15">Registration Number:</th>
        <td class="w-35"> <?php echo $userObj->getRegAlphabet() . "-" . $userObj->getRegYear() . "-" . $userObj->getRegNo(); ?> </td>
    </tr>
    <tr>
        <th class="w-15"> Vehicle Made:</th>
        <td class="w-35"><?php echo $userObj->getVehicleMade(); ?></td>
        <th class="w-15">Vehicle Color:</th>
        <td class="w-35"> <?php echo $userObj->getVehicleColor(); ?></td>
    </tr>
</table>
<div class="row">
    <?php if ($userObj->getPicture() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Picture:
        </div>
        <div class="row">
            <a href="<?php echo UPLOAD_URL . $userObj->getPicture() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getPicture() ?>" width="200px">
            </a>
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getCnicFront() != '') { ?>
    <div class="col-sm">
        <div class="row">
            CNIC Front:
        </div>
        <div class="row">
            <a href="<?php echo UPLOAD_URL . $userObj->getCnicFront() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getCnicFront() ?>" width="200px">
            </a>
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getCnicRear() != '') { ?>
    <div class="col-sm">
        <div class="row">
            CNIC Rear:
        </div>
        <div class="row">
            <a href="<?php echo UPLOAD_URL . $userObj->getCnicRear() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getCnicRear() ?>" width="200px">
            </a>
        </div>
    </div>
    <?php } ?>
    <?php if ($userObj->getRegistration() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Registration:
        </div>
        <div class="row">
            <a href="<?php echo UPLOAD_URL . $userObj->getRegistration() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getRegistration() ?>" width="200px">
            </a>
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getLicence() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Licence:
        </div>
        <div class="row">
           <a href="<?php echo UPLOAD_URL . $userObj->getLicence() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getLicence() ?>" width="200px">
           </a>
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getRoute() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Route:
        </div>
        <div class="row">
            <a href="<?php echo UPLOAD_URL . $userObj->getRoute() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getRoute() ?>" width="200px">
            </a>
        </div>
    </div>
    <?php } ?>


    <?php if ($userObj->getVehicleFront() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Vehicle Front:
        </div>
        <div class="row">
            <a href="<?php echo UPLOAD_URL . $userObj->getVehicleFront() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getVehicleFront() ?>" width="200px">
            </a>
        </div>
    </div>
    <?php } ?>


    <?php if ($userObj->getVehicleRear() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Vehicle Rear:
        </div>
        <div class="row">
            <a href="<?php echo UPLOAD_URL . $userObj->getVehicleFront() ?>" target="_blank">
            <img src="<?php echo UPLOAD_URL . $userObj->getVehicleRear() ?>" width="200px">
            </a>
        </div>
    </div>
    <?php } ?>
</div>








