<?php
require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../partials/header.php";

$ticket_id = $_REQUEST['id'];

$ticketsObj =  new SupportTicket();
$ticketsObj->setId($ticket_id);
$ticketsObj->getSupportTicketById();

$userObj = new User();

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='submit'){

    $newSupportHistoryObj =  new SupportTicketHistory();
    $newSupportHistoryObj->setMessage($_REQUEST['txtName']);
    $newSupportHistoryObj->setSupportTicketId($ticket_id);
    $newSupportHistoryObj->setUserId(1);
    $newSupportHistoryObj->insert();

    $ticketsObj->setMessageCount($ticketsObj->getMessageCount()+1);
    $ticketsObj->setIsUnread(1);
    $ticketsObj->update();


    $payload['message'] = "your ticket is updated! Please check.";
    $payload['key'] = "ticket_updated";
    $payload['ride'] = json_encode($rideObj);
    $fbaseObj = new firebaseNotification();
    $userObj->getUserWithId($ticketsObj->getUserId());
    $token = $userObj->getFirebaseToken();
    $fabseRes = $fbaseObj->sendPayloadOnly(0, $token, $payload, null, 'high',60);


}





$ticketHistoryObj = new SupportTicketHistory();
$ticketHistoryObj->setSupportTicketId($ticket_id);
$tickets = $ticketHistoryObj->getSupportTicketHistory("desc");






?>
<div class="container">
    <form method="post">
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Message:</label>
            <textarea class="form-control" required id="exampleFormControlTextarea1" rows="3" name="txtName"></textarea>
            <input type="hidden" name="id" value="<?php echo $ticket_id?>">
        </div>
        <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
    </form>
</div>
<div class="card">
    <div class="card-header">
        <?php echo  $ticketsObj->getTitle() ?>
    </div>
</div>

<?php foreach($tickets as $ticket){
    $tickHisObj = new SupportTicketHistory();
    $tickHisObj->setAllFields($ticket);
    ?>

    <div class="card">
        <div class="card-body">
            <blockquote class="blockquote mb-0 <?php if($ticketsObj->getUserId()!=$tickHisObj->getUserId()){echo " float-right";} ?> ">
                <p><?php echo $tickHisObj->getMessage() ?></p>
                <footer class="blockquote-footer"><?php $userObj->getUserWithId($tickHisObj->getUserId()); echo $userObj->getName(); ?> <cite title="Source Title"><?php echo $tickHisObj->getCreatedAt(); ?></cite></footer>
            </blockquote>
        </div>
    </div>


<?php }?>


<?php
require_once __DIR__."/../partials/footer.php";

?>