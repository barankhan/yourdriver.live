<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/19/20
 * Time: 10:03 AM
 */
require_once __DIR__."/../partials/header.php";

$ride_id = $_REQUEST['ride_id'];
$rideObj =  new ride();
$rideObj->setId($ride_id);
$rideObj->findRideWithId();

$rideAlertObject = new rideAlert();
$alerts = $rideAlertObject->findAlertsWithDriverInfoByRideId($ride_id);
?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Alert Id</th>
                <th scope="col"> Ride Id</th>
                <th scope="col"> Driver Name</th>
                <th scope="col">Driver Mobile</th>
                <th scope="col">Created At</th>
                <th scope="col">Is Accepted</th>
                <th scope="col">Accepted At</th>
                <th scope="col">Request Received</th>
                <th scope="col">Driver Location</th>
            </tr>
        </thead>
        <tbody>





<?php


$i=0;
foreach ($alerts as $alert){
    ?>
    <tr>

        <td >
            <?php echo $alert['id'] ?>
        </td>
        <td>
            <?php echo $alert['ride_id'] ?>
        </td>
        <td >
            <?php echo $alert['name'] ?>
        </td>
        <td >
            <?php echo $alert['mobile'] ?>
        </td>
        <td >
            <?php echo $alert['created_at'] ?>
        </td>
        <td>
            <?php echo $alert['is_accepted'] ?>
        </td>
        <td>
            <?php echo $alert['accepted_at'] ?>
        </td>
        <td>
            <?php echo $alert['firebase_request_received'] ?>
        </td>

        <?php if($alert['driver_lat']>0) {  ?>
         <td>
            <?php echo "<a target='_blank' href='https://www.google.com/maps/search/?api=1&query=". $alert['driver_lat'].",".$alert['driver_lng']."' class='btn btn-primary'>Location</a>"; ?>
             <a target="_blank" href="https://www.google.com/maps/dir/'<?php echo $rideObj->getPickupLat().",".$rideObj->getPickupLng() ?>'/<?php echo $alert['driver_lat'].",".$alert['driver_lng'] ?>" class='btn btn-primary'>Directions</a>
        </td>
        <?php } ?>



    </tr>

    <?php
}
?>
        </tbody>
    </table>

<?php
echo "Total Alerts: ".$i;
