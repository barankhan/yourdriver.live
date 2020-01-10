<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/10/20
 * Time: 4:39 PM
 */
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['user_id'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();

$userObj = new User();
$userObj->getUserWithId($_REQUEST['user_id']);

$rechargeRequestObj = new RechargeRequest();
$rechargeRequestObj->setTransactionId($_REQUEST['transaction_id']);
$rechargeRequestObj->setAmount($_REQUEST['amount']);
$rechargeRequestObj->setPaymentType($_REQUEST['payment_type']);
$rechargeRequestObj->setUserId($_REQUEST['user_id']);
$response = $rechargeRequestObj->validateTransactionAndInsert();
if($response=='payment_done'){
    $userObj->setResponse("payment_successful");
    $userObj->setBalance($userObj->getBalance()+$rechargeRequestObj->getAmount());
    $userObj->update();
    $userObj->setMessage("Your new Account balance is: ".$userObj->getBalance());
    $tranObj = new DriverTransaction();
    $tranObj->setTransactionType("credit");
    $tranObj->setAmountReceived($rechargeRequestObj->getAmount());
    $tranObj->setAmountReceivedAt(date("Y-m-d H:i:s"));
    $tranObj->setDriverId($rechargeRequestObj->getUserId());
    $tranObj->insert();
}else if($response=='sms_not_found'){
    $userObj->setResponse("voucher_not_found");
    $userObj->setBalance("Your Amount not yet received. We will update your balance later.");
}else if($response=='transaction_already_successful'){
    $userObj->setResponse("already_used");
    $userObj->setBalance("This Transaction ID is already used.");
}else{
    $userObj->setResponse("error");
    $userObj->setBalance("We can't recognize your request! Please contact support!.");
}
$var = json_encode($userObj);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;