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
$search=(empty($_REQUEST['search'])?"":$_REQUEST['search']);

$where = null;
if(strlen($search)>0){
        $where = ' where cnic like "%'.$search.'%" or mobile like "%'.$search.'%"  or  name like "%'.$search.'%"' ;
}

$total_rows = $userObj->getAllUsersCount($where);
$drivers  = $userObj->getAllUsers($where,$page,$limit);

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
            Name
        </div>
        <div class="col-sm">
            Mobile
        </div>
        <div class="col-sm">
            Email
        </div>
        <div class="col-sm">
            Registered At
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
            <?php echo $userObj->getEmail(); ?>
        </div>
        <div class="col-sm">
            <?php echo $userObj->getCreatedAt();?>
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