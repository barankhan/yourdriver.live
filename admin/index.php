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
$rsDriverCount = $userObj->getRegisterDriversCount();
$rsOnlineDriversCount = $userObj->getOnlineDriversCount();





$supportTicketsObj = new SupportTicket();
$supportTicketsRs = $supportTicketsObj->getLatestPendingReplyOpenTicketsCount();

$rideObj = new ride();
$rideRs = $rideObj->getEndedRidesCountInCurrentWeek();
$passengerCancelledRs = $rideObj->getCancelledRidesInCurrentWeek(1);
$driverCancelledRs = $rideObj->getCancelledRidesInCurrentWeek(2);
$unAttendedAutoRides = $rideObj->getUnAttendedAutoAutoRidesCountInCurrentWeek();




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
                Online Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsOnlineDriversCount ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                Register Passengers:
                <span class="badge badge-primary badge-pill"><?php echo $rsUserCount ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                Register Drivers
                <span class="badge badge-primary badge-pill"><?php echo $rsDriverCount ?></span>
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







<?php
require_once __DIR__."/../partials/footer.php";