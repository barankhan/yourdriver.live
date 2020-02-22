<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 5:53 PM
 */
require_once __DIR__."/../partials/header.php";

$userObj = new User();
$drivers  = $userObj->getOnlineDrivers();
if(isset($_REQUEST['msg'])){
    if($_REQUEST['msg']!=''){
        echo "<div class='alert alert-success'>".$_REQUEST['msg']."</div>";
    }
}
?>

    <script type="text/javascript">
        $(document).ready(function() {
            jQuery('.confirmation').on('click', function () {
                return confirm('Are you sure?');
            });
        });
    </script>


    <table class="table table-striped">
    <thead>
    <tr    >
        <td >
            ID
        </td>
        <td >
            Name
        </td>
        <td >
            Father
        </td>
        <td >
            Mobile
        </td>
        <td >
            Vehicle Registration
        </td>
        <td >
            Online At
        </td>
        <td >
            Actions
        </td>


    </tr>
    </thead>
    <tbody>



<?php
$i=0;
foreach ($drivers as $driver){

    $i++;
    $userObj->setAllFields($driver);
    ?>
    <tr  >
        <td >
            <?php echo $userObj->getId(); ?>
        </td>
        <td >
            <?php echo $userObj->getName(); ?>
        </td>
        <td >
            <?php echo $userObj->getFather(); ?>
        </td>
        <td >
                <?php echo $userObj->getMobile();?>
        </td>
        <td >
                <?php echo $userObj->getRegAlphabet()."-".$userObj->getRegYear()."-".$userObj->getRegNo();?>
        </td>
        <td >
            <?php echo $userObj->getOnlineAt();?>
        </td>
        <td >
            <?php echo "<a target='_blank' href='actions/driver_current_location.php?id=".$userObj->getId()."' class='btn btn-primary'>Current Location</a>"; ?>
            <?php echo "<a href='actions/mark_offline.php?id=".$userObj->getId()."' class='btn btn-primary confirmation'>Mark Offline</a>"; ?>
        </td>
    </tr>

<?php
}



echo "Total Online Drivers: ".$i;
?>

    </tbody>
    </table>

<?php
require_once __DIR__."/../partials/footer.php";