<?php
require_once __DIR__."/../../model/LogRequest.php";
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/ride.php";
require_once __DIR__."/../../model/rideAlert.php";
require_once __DIR__."/../../utils/firebaseNotification.php";
$mobile= $_REQUEST['mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();
$userObj = new User();
$userObj->getUserWithMobile($mobile);
$userObj->setFirebaseToken($_REQUEST['firebaseToken']);
$userObj->update();


$rideObj = new ride();

$fabseRes="";
if(!empty($_REQUEST["dropoff_lat_lng"])){
    $dropoff_lat_lng = explode(",",$_REQUEST["dropoff_lat_lng"]);
    $rideObj->setDropoffLat($dropoff_lat_lng[0]);
    $rideObj->setDropoffLng($dropoff_lat_lng[1]);
    $rideObj->setDropoffAddress($_REQUEST['dropoff_address']);

}


$pickup_lat_lng = explode(",",$_REQUEST["pickup_lat_lng"]);
// if user exists insert ride into the database.
if($userObj->getId()>0){
    $rideObj->setPickupLat($pickup_lat_lng[0]);
    $rideObj->setPickupLng($pickup_lat_lng[1]);
    $rideObj->setVehicleType($_REQUEST["vehicle_type"]);
    $rideObj->setPassengerId($userObj->getId());
    $rideObj->setPickupAddress($_REQUEST['pickup_address']);
    $rideObj->setCity(findRideCity::getCity($rideObj->getPickupLat(),$rideObj->getPickupLng()));
    $rideObj->insert();

    $radius = 4;

    if($rideObj->getCity()=='Bahawalpur' && $rideObj->getVehicleType()=='Bike'){
        $radius = 8;
    }

    if($rideObj->getCity()=='Bahawalpur' && $rideObj->getVehicleType()=='Car'){
        $radius = 5;
    }


    $drivers = $userObj->getAvailableDrivers($pickup_lat_lng[0],$pickup_lat_lng[1],$_REQUEST["vehicle_type"],1,$radius);

    if(array_key_exists(0, $drivers)){
        $driver = new User();
        $driver->setAllFields($drivers[0]);
        $driver->setAcceptancePoints($driver->getAcceptancePoints()-5);
        $driver->update();
        $rideAlertObj = new rideAlert();
        $rideAlertObj->setDriverId($driver->getId());
        $rideAlertObj->setRideId($rideObj->getId());
        $rideAlertObj->setDriverLat($driver->getLat());
        $rideAlertObj->setDriverLng($driver->getLng());
        $rideAlertObj->insert();
        // now send push notification to the driver.


        $rideObj->setResponse("alert_sent_to_driver");
        $rideObj->setMessage("waiting for driver to accept the ride");
        $var = json_encode($rideObj);
        echo $var;
        fastcgi_finish_request();

        $fbaseObj = new firebaseNotification();

        $notification['title']='Ride Alert';
        $notification['body']='Passenger is waiting for you.';
        $payload['do']="ride_alert";
        $payload['msg']="You have a new ride";
        $payload['key']="ride_alert";
        $payload['lat']="".$pickup_lat_lng[0];
        $payload['lng']="".$pickup_lat_lng[1];
        $payload['ride_id']="".$rideObj->getId();

        $token = $driver->getFirebaseToken();

        $fabseRes = $fbaseObj->sendPayloadOnly($lr->getId(),$token,$payload,null,'high',60,"ride_alert",$rideAlertObj->getId());





    }else{
        $rideObj->setIsRideCancelled(1);
        $rideObj->update();
        $rideObj->setResponse("no_driver_found");

//        if($_REQUEST["vehicle_type"]!="Car"){
        if($rideObj->getCity()=="invalid_city"){
            $rideObj->setMessage("Sorry, We only operate in Multan & Bahwalpur City.");
        }else{
            $rideObj->setMessage("Sorry no driver found in your area!");
        }

//            $rideObj->setMessage("Due to Crona, Stay home be safe!.");
//        }else{
//            $rideObj->setMessage($_REQUEST["vehicle_type"]." service is launching Soon!");
//            $rideObj->setMessage("Due to Crona, Stay home be safe!.");
//        }


        $var = json_encode($rideObj);
        echo $var;
    }

}else{
    $rideObj->setResponse("mobile_number_not_registered");
    $rideObj->setMessage("Sorry Your mobile number is not register with us!");
    $var = json_encode($rideObj);
    echo $var;
}

$lr->setResponseBody($var);
$lr->updateResponse();





