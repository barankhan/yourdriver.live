<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 2/27/20
 * Time: 3:52 PM
 */
require_once __DIR__."/../partials/header.php";


$userOnlineHistoryObj =  new UserOnlineHistory();

$date = (empty($_REQUEST['date'])?date('Y-m-d'):$_REQUEST['date']);

?>
<script type="text/javascript">

    jQuery(document).ready(function(){
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });


</script>

<form method="get" action="driver_online_hours.php">

    <input type="text" id="datepicker" name="date" value="<?php echo $date?>">
    <input type="submit" class="btn-primary btn">

</form>
<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Date</th>
        <th>Duration(Hours)</th>

    </tr>
    </thead>
    <tbody>
<?php
$history = $userOnlineHistoryObj->getDailyReport($date);
foreach($history as $his){
    $userObj = new User();
    $userObj->getUserWithId($his["user_id"]);
?>
<tr>
<td><?php echo $userObj->getId(); ?></td>
<td><?php echo $userObj->getName(); ?></td>
<td><?php echo $userObj->getMobile(); ?></td>
<td><?php echo $date; ?></td>
<td><?php echo bcdiv(intdiv($his['duration'], 60),1,2).':'. bcdiv(($his['duration'] % 60),1,2); ?></td>

</tr>
<?php

}

?>
    </tbody>
</table>