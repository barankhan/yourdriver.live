<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/19/20
 * Time: 9:23 AM
 */

require_once __DIR__."/../partials/header.php";

$ridesObj = new ride();
$page = (empty($_REQUEST['page'])?1:$_REQUEST['page']);
$limit = (empty($_REQUEST['limit'])?10:$_REQUEST['limit']);
$search=(empty($_REQUEST['search'])?"":$_REQUEST['search']);

//$where = null;
//if(strlen($search)>0){
////    $where = ' where cnic like "%'.$search.'%" or mobile like "%'.$search.'%"  or  name like "%'.$search.'%"' ;
//}

$total_rows = $ridesObj->getCancelledByPassengerAutoRidesCount();
$unattended_rides  = $ridesObj->getCancelledByPassengerAutoRides($page,$limit);

$total_pages = ceil($total_rows/$limit);



$i=0;
?>

    <div class="row align-content-center">
        <form method="get" >
            <input type="text" name="search" value="<?php echo $search ?>"/>
            <input type="hidden" name="page" value="<?php echo $page?>" />
            <button type="submit" value="search" class="btn btn-primary">Search</button>
        </form>
    </div>


    <div class="container">

    <div class="row" style="background-color: <?php echo ($i++%2==0?'#dcdcdc':'#aaaaaa'); ?>"   >
        <div class="col-sm">
            ID
        </div>
        <div class="col-sm">
            Passenger Id
        </div>
        <div class="col-sm">
            Passenger Name
        </div>
        <div class="col-sm">
            Created At
        </div>
        <div class="col-sm">
            Alert Count
        </div>
        <div class="col-sm">
           Actions
        </div>



    </div>


<?php

foreach ($unattended_rides as $ride){

    ?>
    <div class="row  p-2" style="background-color: <?php echo ($i++%2==0?'#dcdcdc':'#aaaaaa'); ?>"   >
        <div class="col-sm">
            <?php echo $ride['id'] ?>
        </div>
        <div class="col-sm">
            <?php echo $ride['passenger_id'] ?>
        </div>

        <div class="col-sm">
            <?php echo $ride['name'] ?>
        </div>
        <div class="col-sm">
            <?php echo $ride['created_at'];?>
        </div>
        <div class="col-sm">
            <?php if($ride['alert_count']>0){ ?>
            <a href="alert_details.php?ride_id=<?php  echo $ride['id'] ?> " class="btn btn-primary"><?php echo $ride['alert_count']; ?></a>
            <?php }else{ echo $ride['alert_count']; } ?>
        </div>
        <div class="col-sm p-2">
            <?php echo "<a target='_blank' href='https://www.google.com/maps/search/?api=1&query=". $ride['pickup_lat'].",".$ride['pickup_lng']."' class='btn btn-primary'>Pickup</a>"; ?>
        </div>
        <?php if ($ride['dropoff_lat']>0){ ?>
        <div class="col-sm p-2">
            <?php echo "<a target='_blank' href='https://www.google.com/maps/search/?api=1&query=". $ride['dropoff_lat'].",".$ride['dropoff_lng']."' class='btn btn-primary'>Dropoff</a>"; ?>
        </div>
        <?php } ?>

    </div>

    <?php
}
?>
<?php  if($total_pages>1){ ?>

    <nav aria-label="Page navigation example">
        <ul class="pagination mt-2">

            <li class="page-item"><a class="page-link" href="?page=1">First</a></li>

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

                <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i ?></a></li>

            <?php } ?>




            <?php
            $y = 0;
            for($i=$page;$i<=$total_pages;$i++){

                $y++;
                if($y>3){
                    break;
                }
                ?>

                <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i ?></a></li>

            <?php }
            ?>

            <li class="page-item"><a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Next</a></li>


            <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>">Last</a></li>
        </ul>
    </nav>

<?php } ?>
<?php
require_once __DIR__."/../partials/footer.php";