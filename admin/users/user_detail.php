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
<div class="row">
    <div class="col-sm">
        Name:
    </div>
    <div class="col-sm">
        <?php echo $userObj->getName(); ?>
    </div>
    <div class="col-sm">
        Father:
    </div>
    <div class="col-sm">
        <?php echo $userObj->getFather(); ?>
    </div>
    <div class="col-sm">
        CNIC:
    </div>
    <div class="col-sm">
        <?php echo $userObj->getCnic(); ?>
    </div>

    <div class="col-sm">
        Registration Number:
    </div>
    <div class="col-sm">
        <?php echo $userObj->getRegAlphabet() . "-" . $userObj->getRegYear() . "-" . $userObj->getRegNo(); ?>
    </div>
</div>
<div class="row">
    <?php if ($userObj->getPicture() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Picture:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getPicture() ?>" width="200px">
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getCnicFront() != '') { ?>
    <div class="col-sm">
        <div class="row">
            CNIC Front:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getCnicFront() ?>" width="200px">
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getCnicRear() != '') { ?>
    <div class="col-sm">
        <div class="row">
            CNIC Rear:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getCnicRear() ?>" width="200px">
        </div>
    </div>
    <?php } ?>
    <?php if ($userObj->getRegistration() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Registration:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getRegistration() ?>" width="200px">
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getLicence() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Licence:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getLicence() ?>" width="200px">
        </div>
    </div>
    <?php } ?>

    <?php if ($userObj->getRoute() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Route:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getRoute() ?>" width="200px">
        </div>
    </div>
    <?php } ?>


    <?php if ($userObj->getVehicleFront() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Vehicle Front:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getVehicleFront() ?>" width="200px">
        </div>
    </div>
    <?php } ?>


    <?php if ($userObj->getVehicleRear() != '') { ?>
    <div class="col-sm">
        <div class="row">
            Vehicle Rear:
        </div>
        <div class="row">
            <img src="<?php echo UPLOAD_URL . $userObj->getVehicleRear() ?>" width="200px">
        </div>
    </div>
    <?php } ?>
</div>








