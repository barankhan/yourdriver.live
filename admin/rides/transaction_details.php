<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/25/20
 * Time: 5:39 PM
 */

require_once __DIR__."/../partials/header.php";

$rideId = $_REQUEST["ride_id"];

$rideObj = new ride();
$rideObj->setId($rideId);
$rideObj->findRideWithId();


$transactionObj = new DriverTransaction();
$transactionObj->setRideId($rideId);
$transactionObj->findByRideId();


$driverObj = new User();
$driverObj->getUserWithId($transactionObj->getDriverId());


$passengerObj = new User();
$passengerObj->getUserWithId($transactionObj->getPassengerId());


$transactionLiabilitiesObj = new TransactionLiability();
$transactionLiabilitiesObj->setTransactionId($transactionObj->getId());
$liabilities = $transactionLiabilitiesObj->findByTransactionId();


?>
<table class="table table-striped">
    <tr class="table-primary">
        <th colspan="2"> Passenger Information </th>
        <th colspan="2"> Driver Information Information </th>
    </tr>
    <tr>
        <th class="w-15">Name</th>
        <td class="w-35"><?php echo $passengerObj->getId()."-".$passengerObj->getName(); ?></td>
        <th class="w-15">Name</th>
        <td class="w-35"><?php echo $driverObj->getId()."-".$driverObj->getName(); ?> </td>
    </tr>
    <tr>
        <th class="w-15">Initial Balance</th>
        <td class="w-35"><?php echo $transactionObj->getPassengerInitialBalance() ?></td>
        <th class="w-15">Initial Balance</th>
        <td class="w-35"><?php echo $transactionObj->getDriverInitialBalance(); ?> </td>
    </tr>
    <tr>
        <th class="w-15">New Balance</th>
        <td class="w-35"><?php echo $transactionObj->getPassengerNewBalance(); ?></td>
        <th class="w-15">New Balance</th>
        <td class="w-35"><?php echo $transactionObj->getDriverNewBalance(); ?> </td>



    </tr>
</table>

<table class="table table-striped">



    <tr class="table-primary">
        <th colspan="2"> Transaction Information </th>

    </tr>
    <tr>
        <th class="w-25">
            Transaction ID:
        </th>
        <td>
            <?php echo $transactionObj->getId(); ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Transaction Type:
        </th>
        <td>
            <?php echo $transactionObj->getTransactionType(); ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Distance(KM): <?php echo $transactionObj->getKmTravelled() ?> x <?php echo $transactionObj->getKmTravelledRate() ?>
        </th>
        <td>
            <?php echo $transactionObj->getKmTravelled()*$transactionObj->getKmTravelledRate() ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Startup Fare:
        </th>
        <td>
            <?php echo $transactionObj->getDriverStartUpFare(); ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Company Charges:
        </th>
        <td>
            <?php echo $transactionObj->getCompanyServiceCharges(); ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Distance(KM): <?php echo $transactionObj->getTimeElapsedMinutes() ?> x <?php echo $transactionObj->getTimeElapsedRate() ?>
        </th>
        <td>
            <?php echo $transactionObj->getTimeElapsedMinutes()*$transactionObj->getTimeElapsedRate() ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Total Fare:
        </th>
        <td>
            <?php echo $transactionObj->getTotalFare(); ?>
        </td>
    </tr>

    <tr>
        <th class="w-25">
            Payable Amount:
        </th>
        <td>
            <?php echo $transactionObj->getPayableAmount(); ?>
        </td>
    </tr>

    <?php foreach($liabilities as $liability) {
        $liabilityObj = new TransactionLiability();
        $liabilityObj->setAllFields($liability);
        ?>
    <tr>
        <th class="w-25">
            <?php echo $liabilityObj->getTitle() ?>
        </th>
        <td>
            <?php echo $liabilityObj->getAmount() ?>
        </td>
    </tr>
    <?php } ?>

    <tr>
        <th class="w-25">
            Amount Received:
        </th>
        <td>
            <?php echo $transactionObj->getAmountReceived(); ?>
        </td>
    </tr>

    <tr>
        <th class="w-25">
            Transaction Date:
        </th>
        <td>
            <?php echo $transactionObj->getCreatedAt(); ?>
        </td>
    </tr>

    <tr class="table-primary">
        <th colspan="2"> Ride Information </th>
    </tr>

    <tr>
        <th class="w-25">
            Ride Registered At:
        </th>
        <td>
            <?php echo $rideObj->getCreatedAt(); ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Driver Arrived At:
        </th>
        <td>
            <?php echo $rideObj->getDriverArrivedAt(); ?>
        </td>
    </tr>

    <tr>
        <th class="w-25">
            Ride Started At:
        </th>
        <td>
            <?php echo $rideObj->getRideStartedAt(); ?>
        </td>
    </tr>


    <tr>
        <th class="w-25">
            Ride Ended At:
        </th>
        <td>
            <?php echo $rideObj->getRideEndedAt(); ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Rating
        </th>
        <td>
            <?php echo $rideObj->getRating(); ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Pickup Address
        </th>
        <td>
            <?php echo $rideObj->getPickupAddress() ?>
        </td>
    </tr>
    <tr>
        <th class="w-25">
            Dropoff Address
        </th>
        <td>
            <?php echo $rideObj->getDropoffAddress(); ?>
        </td>
    </tr>

</table>

