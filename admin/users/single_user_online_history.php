<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 5:53 PM
 */
require_once __DIR__."/../partials/header.php";

$userObj = new User();
$page = (empty($_REQUEST['page'])?1:$_REQUEST['page']);
$limit = (empty($_REQUEST['limit'])?10:$_REQUEST['limit']);

$where = null;

$userObj->getUserWithId($_REQUEST['id']);

$userOnlineHistory = new UserOnlineHistory();
$userOnlineHistory->setUserId($userObj->getId());
$rows = $userOnlineHistory->getUserReport($page,$limit);



$total_rows = $userOnlineHistory->getUserReportCount();


$total_pages = ceil($total_rows/$limit);



$i=0;
?>
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
        <tr>
            <th class="w-15">city:</th>
            <td class="w-35"><?php echo $userObj->getCity(); ?> </td>
        </tr>
    </table>



    <table class="table table-striped">
    <thead>

    <tr >
        <td >
            Date
        </td>
        <td >
            Duration (hours)
        </td>



    </tr>
    </thead>
    <tbody>


<?php

foreach ($rows as $row){

    ?>
    <tr >
        <td >
            <?php echo $row['created_date']; ?>
        </td>
        <td >
            <?php echo sprintf("%02s", intdiv($row['mins'], 60)).':'. sprintf("%02s",$row['mins'] % 60); ?>
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

            <li class="page-item"><a class="page-link" href="?page=1&id=<?php echo $userObj->getId() ?>">First</a></li>

            <li class="page-item"><a class="page-link" href="<?php if($page == 1){ echo '#'; } else { echo "?page=".($page-1); } ?>&id=<?php echo $userObj->getId() ?>">Previous</a></li>


            <?php
            if($page>1)
                $y = 0;
            for($i=1;$i<$page;$i++){

                $y++;
                if($y>3){
                    break;
                }
                ?>

                <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>&id=<?php echo $userObj->getId() ?>"><?php echo $i ?></a></li>

            <?php } ?>




            <?php
                $y = 0;
                for($i=$page;$i<=$total_pages;$i++){

                    $y++;
                    if($y>3){
                        break;
                    }
                    ?>

                    <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>&id=<?php echo $userObj->getId() ?>"><?php echo $i ?></a></li>

                <?php }
                ?>

            <li class="page-item"><a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>&id=<?php echo $userObj->getId() ?>">Next</a></li>


            <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>&id=<?php echo $userObj->getId() ?>">Last</a></li>
        </ul>
    </nav>

<?php } ?>
<?php
require_once __DIR__."/../partials/footer.php";