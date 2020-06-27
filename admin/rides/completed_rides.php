<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/19/20
 * Time: 9:23 AM
 */

require_once __DIR__."/../partials/header.php";

$vehicle_type = (empty($_REQUEST['vehicle_type'])?"Auto":$_REQUEST['vehicle_type']);

$ridesObj = new ride();
$page = (empty($_REQUEST['page'])?1:$_REQUEST['page']);
$limit = (empty($_REQUEST['limit'])?10:$_REQUEST['limit']);
$search=(empty($_REQUEST['search'])?"":$_REQUEST['search']);

//$where = null;
//if(strlen($search)>0){
////    $where = ' where cnic like "%'.$search.'%" or mobile like "%'.$search.'%"  or  name like "%'.$search.'%"' ;
//}

$total_rows = $ridesObj->getCompletedRidesCount($vehicle_type);
$unattended_rides  = $ridesObj->getCompletedRides($vehicle_type,$page,$limit);

$total_pages = ceil($total_rows/$limit);



$i=0;
?>
    <h1>Completed Rides "<?php echo $vehicle_type; ?>"</h1>
    <div class="row align-content-center">
        <form method="get" >
            <input type="text" name="search" value="<?php echo $search ?>"/>
            <input type="hidden" name="page" value="<?php echo $page?>" />
            <button type="submit" value="search" class="btn btn-primary">Search</button>
        </form>
    </div>


    <table class="table table-striped">
    <thead>
    <tr>
        <th>
            ID
        </th>

        <th>
            Driver
        </th>
        <th>
            Driver Balance
        </th>
        <th>
            Passenger
        </th>
        <th>
            Passenger balance
        </th>
        <th>
            Created At
        </th>
        <th>
            Alert Count
        </th>
        <th>
            Total Fare
        </th>
        <th>
            Amount Received
        </th>
        <th>
           Actions
        </th>
    </tr>
    </thead>
    <tbody>
<?php

foreach ($unattended_rides as $ride){

    ?>
    <tr>
        <td >
            <?php echo $ride['id'] ?>
        </td>


        <td >
            <?php echo $ride['driver_id'] ?>-<?php echo $ride['name'] ?>
        </td>
        <td>
            <?php echo $ride['driver_balance'];?>
        </td>
        <td >
            <?php echo $ride['passenger_id'] ?>-<?php echo $ride['passenger_name'] ?>
        </td>
        <td>
            <?php echo $ride['passenger_balance'];?>
        </td>
        <td >
            <?php echo $ride['created_at'];?>
        </td>
        <td >
            <?php if($ride['alert_count']>0){ ?>
            <a href="alert_details.php?ride_id=<?php  echo $ride['id'] ?> " class="btn btn-primary"><?php echo $ride['alert_count']; ?></a>
            <?php }else{ echo $ride['alert_count']; } ?>
        </td>
        <td>
            <?php echo $ride['total_fare'];?>
        </td>
        <td>
            <?php echo $ride['amount_received'];?>
        </td>



        <td>
            <?php echo "<a target='_blank' href='https://www.google.com/maps/search/?api=1&query=". $ride['pickup_lat'].",".$ride['pickup_lng']."' class='btn btn-primary'>Pickup</a>"; ?>
            <?php if ($ride['dropoff_lat']>0){ ?>
                <?php echo "<a target='_blank' href='https://www.google.com/maps/search/?api=1&query=". $ride['dropoff_lat'].",".$ride['dropoff_lng']."' class='btn btn-primary'>Dropoff</a>"; ?>
            <?php } ?>
            <a class='btn btn-primary' href="transaction_details.php?ride_id=<?php echo $ride['id'] ?>">Transaction</a>
        </td>
    </tr>

    <?php
}
?>
    </tbody>
    </table>
<?php  if($total_pages>1){ ?>

    <nav aria-label="Page navigation example">
        <ul class="pagination mt-2">

            <li class="page-item"><a class="page-link" href="?page=1&vehicle_type=<?php echo $vehicle_type ?>">First</a></li>

            <li class="page-item"><a class="page-link" href="<?php if($page == 1){ echo '#'; } else { echo "?page=".($page-1); } ?>">Previous</a></li>


            <?php
            if($page>1)
                $y = 0;
            for($i=1;$i<$page;$i++){

                $y++;
                if($y>3){
                    break;
                }
                ?>

                <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>&vehicle_type=<?php echo $vehicle_type ?>"><?php echo $i ?></a></li>

            <?php } ?>




            <?php
            $y = 0;
            for($i=$page;$i<=$total_pages;$i++){

                $y++;
                if($y>3){
                    break;
                }
                ?>

                <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>&vehicle_type=<?php echo $vehicle_type ?>"><?php echo $i ?></a></li>

            <?php }
            ?>

            <li class="page-item"><a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1)."&vehicle_type=".$vehicle_type;  } ?>">Next</a></li>


            <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>&vehicle_type=<?php echo $vehicle_type ?>">Last</a></li>
        </ul>
    </nav>

<?php } ?>
<?php
require_once __DIR__."/../partials/footer.php";