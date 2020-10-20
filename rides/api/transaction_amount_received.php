<?php
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();


$amount_received = $_REQUEST['amount_received'];

$transObj = new DriverTransaction();
$transObj->setId($_REQUEST["trans_id"]);
$transObj->findById();

$passengerObj = new User();
$passengerObj->getUserWithId($transObj->getPassengerId());

$driverObj = new User();
$driverObj->getUserWithId($transObj->getDriverId());

$triggerFirebaseToPassenger=false;

if($transObj->getTransactionCompleted()==0){
    // Transaction has not be received by the driver.

    if($amount_received>$transObj->getPayableAmount()){
        // if passenger pays more then payable amount;
        if(($amount_received-$transObj->getPayableAmount())+$passengerObj->getBalance()<=PASSENGER_WALLET_LIMIT){
            $transObj->setAmountReceived($amount_received);
            $transObj->setAmountReceivedAt(date("Y-m-d H:i:s"));
            $transObj->setTotalAmount();
//            $transObj->setCompanyInwardHead("Balance_added");
//            $transObj->setInwardHeadAmount($amount_received-$transObj->getPayableAmount());

            $liabilityObj  =  new TransactionLiability();
            $liabilityObj->setTitle("Balance_added");
            $liabilityObj->setLiabilityTypeId(1);
            $liabilityObj->setAmount($amount_received-$transObj->getPayableAmount());
            $liabilityObj->setTransactionId($transObj->getId());
            $liabilityObj->insert();

            $transObj->setTransactionCompleted(1);


            $triggerFirebaseToPassenger = true;
            
            $passengerObj->setBalance($passengerObj->getBalance()+$amount_received-$transObj->getPayableAmount());
            $passengerObj->update();

            $driverObj->setBalance($driverObj->getBalance()-($amount_received-$transObj->getPayableAmount()));
            $driverObj->update();

            $transObj->setPassengerNewBalance($passengerObj->getBalance());
            $transObj->setDriverNewBalance($driverObj->getBalance());

            $transObj->update();
            $transObj->settleCancelledAmount();
            $transObj->setResponse("amount_update_success");
            $transObj->setMessage("Your Transaction is completed successfully");


        }else{
            $transObj->setResponse("wallet_in_access");
            $transObj->setMessage("You can only collect Rs.".($transObj->getPayableAmount()+PASSENGER_WALLET_LIMIT-$passengerObj->getBalance()));
        }
    }
    else if($amount_received==round($transObj->getPayableAmount())){
        // if passenger pays Only Payable amount;
        $transObj->setAmountReceived($amount_received);
        $transObj->setAmountReceivedAt(date("Y-m-d H:i:s"));
        $transObj->setTotalAmount();
        $transObj->setTransactionCompleted(1);
        $transObj->update();
        $transObj->settleCancelledAmount();
        $transObj->setResponse("amount_update_success");
        $transObj->setMessage("Your Transaction is completed successfully");
        $triggerFirebaseToPassenger = true;
    }else{
        // if passenger pays less than payable amount.
        $transObj->setResponse("less_amount_entered");
        $transObj->setMessage("Please collect at least Rs.".$transObj->getPayableAmount());

    }
}else{
    $transObj->setResponse("already_completed");
    $transObj->setMessage("This transaction has already been completed!");
}


$res = array("user"=>$driverObj,"transaction"=>$transObj,"response"=>$transObj->getResponse(),"message"=>$transObj->getMessage());
$var = json_encode($res);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;
//fastcgi_finish_request();

if($triggerFirebaseToPassenger){

    // for the settlement of cancel amount we need to fetch the user current object;

    $passengerObj->getUserWithId($passengerObj->getId());


    $payload['message']='Driver has received Rs. '.$transObj->getAmountReceived().', Your balance is Rs.'.$passengerObj->getBalance();
    $payload['key']="p_amount_received";
    $payload['user']=json_encode($passengerObj);
    $fbaseObj = new firebaseNotification();
    $token = $passengerObj->getFirebaseToken();

    $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,'high');

}





