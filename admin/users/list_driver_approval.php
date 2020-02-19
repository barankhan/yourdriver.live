<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 5:53 PM
 */
require_once __DIR__."/../partials/header.php";

$userObj = new User();
$drivers  = $userObj->getAllApprovalRequiredDrivers();
?>

    <table class="table table-striped">
    <thead>

    <tr >
        <td >
            ID
        </td>
        <td >
            Name
        </td>
        <td>
            Fater
        </td>
        <td>
            CNIC
        </td>
        <td>
            Vehicle No
        </td>
        <td>
            Created at
        </td>
        <td>
            Actions
        </td>


    </tr>
    </thead>
    <tbody>



<?php
$i=0;
foreach ($drivers as $driver){


    $userObj->setAllFields($driver);
    ?>
    <tr  >
        <td>
            <?php echo $userObj->getId(); ?>
        </td><td>
            <?php echo $userObj->getName(); ?>
        </td>
        <td >
            <?php echo $userObj->getFather(); ?>
        </td>
        <td >
                <?php echo $userObj->getCnic();?>
        </td>
        <td >
                <?php echo $userObj->getRegAlphabet()."-".$userObj->getRegYear()."-".$userObj->getRegNo();?>
        </td>
        <td >
            <?php echo $userObj->getCreatedAt();?>
        </td>
        <td >
            <?php echo "<a href='user_detail.php?id=".$userObj->getId()."' class='btn btn-primary'>Detail</a>"; ?>
            <?php echo "<a href='approve_driver.php?id=".$userObj->getId()."' class='btn btn-primary'>Approve</a>"; ?>
        </td>

    </tr>

<?php
}
?>

    </tbody>
    </table>
<?php
require_once __DIR__."/../partials/footer.php";