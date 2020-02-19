<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/19/20
 * Time: 10:03 AM
 */
require_once __DIR__."/../partials/header.php";

$ride_id = $_REQUEST['ride_id'];

$rideAlertObject = new rideAlert();
$alerts = $rideAlertObject->findAlertsWithDriverInfoByRideId($ride_id);
?>
    <div class="row">
        <div class="col-sm">
            Alert Id
        </div>
        <div class="col-sm">
            Ride Id
        </div>
        <div class="col-sm">
            Driver Name
        </div>
        <div class="col-sm">
           Created At
        </div>
        <div class="col-sm">
            Is Accepted
        </div>
        <div class="col-sm">
            Accepted At
        </div>
    </div>


<?php


$i=0;
foreach ($alerts as $alert){
    ?>
    <div class="row p-2" style="background-color: <?php echo ($i++%2==0?'#dcdcdc':'#aaaaaa'); ?>"   >
        <div class="col-sm">
            <?php echo $alert['id'] ?>
        </div>
        <div class="col-sm">
            <?php echo $alert['ride_id'] ?>
        </div>
        <div class="col-sm">
            <?php echo $alert['name'] ?>
        </div>
        <div class="col-sm">
            <?php echo $alert['created_at'] ?>
        </div>
        <div class="col-sm">
            <?php echo $alert['is_accepted'] ?>
        </div>
        <div class="col-sm">
            <?php echo $alert['accepted_at'] ?>
        </div>


    </div>

    <?php
}

echo "Total Alerts: ".$i;
