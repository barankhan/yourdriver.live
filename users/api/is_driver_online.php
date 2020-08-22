<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/6/19
 * Time: 1:38 PM
 */

require_once __DIR__."/../../vendor/autoload.php";

$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();

$flag = $_REQUEST['is_driver_online'];
$app_version = $_REQUEST['app_version'];
$userObj = new User();
$userObj->getUserWithMobile($_REQUEST['mobile']);

//echo $userObj->getBalance()."<br/>".$userObj->getCreditLimit()."<br/>".$flag;
if($userObj->getBalance()>$userObj->getCreditLimit() && $flag==1){
    $userObj->setIsDriverOnline($flag);
    $userObj->setMessage("Online");
    $userObj->setResponse("success");
    $userObj->setOnlineAt(date('Y-m-d H:i:s'));
}elseif ($flag==0){
    $userObj->setMessage("Offline");
    $userObj->setResponse("success");
    $userObj->setIsDriverOnline($flag);
}
else{
    $userObj->setMessage("Sorry! You can't go online.Recharge your Account!");
    $userObj->setResponse("success");
}



$userObj->setFirebaseToken($_REQUEST['firebaseToken']);
if($userObj->getIsDriverOnline()==0 && $flag==0){
    $userObj->setLat(0);
    $userObj->setLng(0);
    $d = date('Y-m-d H:i:s');
    $userObj->setOfflineAt($d);


    if($userObj->getOnlineAt()!=null){
        $onlineHistoryObj = new UserOnlineHistory();
        $onlineHistoryObj->setOfflineAt($d);
        $onlineHistoryObj->setUserId($userObj->getId());
        $onlineHistoryObj->setOnlineAt($userObj->getOnlineAt());

        $start = strtotime($userObj->getOnlineAt());
        $end = strtotime($d);
        $mins = ($end - $start) / 60;

        $onlineHistoryObj->setDurationInMinutes($mins);
        $onlineHistoryObj->insert();

    }

    $userObj->setMessage("Offline");
    $userObj->setResponse("success");
}

if(!$userObj->update())
{
     $userObj->setResponse("error");
     $userObj->setMessage("Sorry you can't Change your status now.");
}
$lr->setResponseBody(json_encode($userObj));
$lr->updateResponse();
echo json_encode($userObj);