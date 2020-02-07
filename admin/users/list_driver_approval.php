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




    <div class="container">
<?php
$i=0;
foreach ($drivers as $driver){


    $userObj->setAllFields($driver);
    ?>
    <div class="row" style="background-color: <?php echo ($i++%2==0?'#dcdcdc':'#aaaaaa'); ?>"   >
        <div class="col-sm">
            <?php echo $userObj->getName(); ?>
        </div>
        <div class="col-sm">
            <?php echo $userObj->getFather(); ?>
        </div>
        <div class="col-sm">
                <?php echo $userObj->getCnic();?>
        </div>
        <div class="col-sm">
                <?php echo $userObj->getRegAlphabet()."-".$userObj->getRegYear()."-".$userObj->getRegNo();?>
        </div>
        <div class="col-sm">
            <?php echo $userObj->getCreatedAt();?>
        </div>
        <div class="col-sm">
            <?php echo "<a href='user_detail.php?id=".$userObj->getId()."' class='btn btn-primary'>Detail</a>"; ?>
        </div>
        <div class="col-sm">
                <?php echo "<a href='approve_driver.php?id=".$userObj->getId()."' class='btn btn-primary'>Approve</a>"; ?>
        </div>
    </div>

<?php
}
?>



<?php
require_once __DIR__."/../partials/footer.php";