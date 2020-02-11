<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/4/19
 * Time: 5:53 PM
 */
require_once __DIR__."/../partials/header.php";

if(isset($_REQUEST['msg'])){
    if($_REQUEST['msg']!=''){
        echo "<div class='alert alert-success'>".$_REQUEST['msg']."</div>";
    }
}

$userObj = new User();
$page = (empty($_REQUEST['page'])?1:$_REQUEST['page']);
$limit = (empty($_REQUEST['limit'])?10:$_REQUEST['limit']);

$total_rows = $userObj->getCountNotVerifiedUsers();

$total_pages = ceil($total_rows/$limit);

$drivers  = $userObj->getAllUnVerifiedUsers($page,$limit);

$i=0;
?>




    <div class="container">

    <div class="row" style="background-color: <?php echo ($i++%2==0?'#dcdcdc':'#aaaaaa'); ?>"   >
        <div class="col-sm">
            ID
        </div>
        <div class="col-sm">
            Name
        </div>
        <div class="col-sm">
            Mobile
        </div>
        <div class="col-sm">
            Verification Token
        </div>
        <div class="col-sm">
            Registered At
        </div>
        <div class="col-sm">
            Actions
        </div>


    </div>


<?php

foreach ($drivers as $driver){
    $userObj->setAllFields($driver);
    ?>
    <div class="row  p-2" style="background-color: <?php echo ($i++%2==0?'#dcdcdc':'#aaaaaa'); ?>"   >
        <div class="col-sm">
            <?php echo $userObj->getId(); ?>
        </div>
        <div class="col-sm">
            <?php echo $userObj->getName(); ?>
        </div>
        <div class="col-sm">
            <?php echo $userObj->getMobile(); ?>
        </div>
        <div class="col-sm">
            <?php echo $userObj->getVerificationToken(); ?>
        </div>
        <div class="col-sm">
            <?php echo $userObj->getCreatedAt();?>
        </div>
        <div class="col-sm">
            <a class="btn btn-primary" href="actions/resend_sms.php?id=<?php echo $userObj->getId(); ?>">Resend SMS</a>
        </div>

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