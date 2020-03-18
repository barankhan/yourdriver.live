<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/30/20
 * Time: 3:24 PM
 */
require_once __DIR__."/partials/header.php";

$userObj = new User();
$rs = $userObj->getCurrentWeekRegistrationCounts();


$rsUserCount = $userObj->getRegisterUsersCount();
$rsAutoDriverCount = $userObj->getRegisterDriversCount();
$rsBikeDriverCount = $userObj->getRegisterDriversCount("Bike");
$rsCarDriverCount = $userObj->getRegisterDriversCount("Car");
$rsAutoOnlineDriversCount = $userObj->getOnlineDriversCount();
$rsBikeOnlineDriversCount = $userObj->getOnlineDriversCount("Bike");
$rsCarOnlineDriversCount = $userObj->getOnlineDriversCount("Car");





$supportTicketsObj = new SupportTicket();
$supportTicketsRs = $supportTicketsObj->getLatestPendingReplyOpenTicketsCount();

$rideObj = new ride();
$rideRs = $rideObj->getEndedRidesCountInCurrentWeek();
$passengerCancelledRs = $rideObj->getCancelledRidesInCurrentWeek(1);
$driverCancelledRs = $rideObj->getCancelledRidesInCurrentWeek(2);

$unAttendedAutoRides = $rideObj->getUnAttendedRidesCountInCurrentWeek("Auto");
$unAttendedBikeRides = $rideObj->getUnAttendedRidesCountInCurrentWeek("Bike");
$unAttendedCarRides = $rideObj->getUnAttendedRidesCountInCurrentWeek("Car");





?>

    <div class="card float-left ml-2" style="width: 18rem;">
        <div class="card-header">
            Stats
        </div>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Unanswered tickets
                <span class="badge badge-primary badge-pill"><?php echo $supportTicketsRs['ct']==0?"Zero":$supportTicketsRs['ct']; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Auto Online Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsAutoOnlineDriversCount ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Bike Online Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsBikeOnlineDriversCount ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Car Online Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsCarOnlineDriversCount ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                Registered Passengers:
                <span class="badge badge-primary badge-pill"><?php echo $rsUserCount ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                Auto Registered Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsAutoDriverCount ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                Bike Registered Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsBikeDriverCount ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Car Registered Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsCarDriverCount ?></span>
            </li>
        </ul>
    </div>



    <div class="card float-left  ml-2" style="width: 18rem;">
        <div class="card-header">
            Registration Counts
        </div>
        <ul class="list-group">
            <?php foreach($rs as $r) { ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $r['created_date'] ?>
                    <span class="badge badge-primary badge-pill"><?php echo $r['ct'] ?></span>
                </li>


            <?php } ?>
        </ul>
    </div>

    <div class="card float-left  ml-2" style="width: 18rem;">
        <div class="card-header">
            Completed Rides
        </div>
        <ul class="list-group">
            <?php foreach($rideRs as $r) { ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $r['ride_ended_at'] ?>
                    <span class="badge badge-primary badge-pill"><?php echo $r['ct'] ?></span>
                </li>


            <?php } ?>
        </ul>
    </div>



    <div class="card float-left  ml-2" style="width: 18rem;">
        <div class="card-header">
            Cancelled by passenger
        </div>
        <ul class="list-group">
            <?php foreach($passengerCancelledRs as $r) { ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $r['ride_cancelled_at'] ?>
                    <span class="badge badge-primary badge-pill"><?php echo $r['ct'] ?></span>
                </li>


            <?php } ?>
        </ul>
    </div>



    <div class="card float-left  ml-2" style="width: 18rem;">
        <div class="card-header">
            Cancelled by driver
        </div>
        <ul class="list-group">
            <?php foreach($driverCancelledRs as $r) { ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $r['ride_cancelled_at'] ?>
                    <span class="badge badge-primary badge-pill"><?php echo $r['ct'] ?></span>
                </li>


            <?php } ?>
        </ul>
    </div>

    <div class="clearfix"></div>


    <div class="card float-left  ml-2" style="width: 18rem;">
        <div class="card-header">
            Auto Driver Not found
        </div>
        <ul class="list-group">
            <?php foreach($unAttendedAutoRides as $r) { ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $r['created_at'] ?>
                    <span class="badge badge-primary badge-pill"><?php echo $r['ct'] ?></span>
                </li>


            <?php } ?>
        </ul>
    </div>


    <div class="card float-left  ml-2" style="width: 18rem;">
        <div class="card-header">
            Bike Driver Not found
        </div>
        <ul class="list-group">
            <?php foreach($unAttendedBikeRides as $r) { ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $r['created_at'] ?>
                    <span class="badge badge-primary badge-pill"><?php echo $r['ct'] ?></span>
                </li>


            <?php } ?>
        </ul>
    </div>

    <div class="card float-left  ml-2" style="width: 18rem;">
        <div class="card-header">
            Car Driver Not found
        </div>
        <ul class="list-group">
            <?php foreach($unAttendedCarRides as $r) { ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $r['created_at'] ?>
                    <span class="badge badge-primary badge-pill"><?php echo $r['ct'] ?></span>
                </li>


            <?php } ?>
        </ul>
    </div>







<?php
require_once __DIR__."/../partials/footer.php";