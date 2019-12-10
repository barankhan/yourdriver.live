<?php
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/LogRequest.php";
$target_dir = __DIR__."/../../uploads/";

$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST).json_encode($_FILES));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->setId($lr->insertLog());

$mobile = $_REQUEST['mobile'];


$userObj = new User();
$userObj->getUserWithMobile($_REQUEST['mobile']);

$route = explode(".",$_FILES["route"]["name"]);
$registration = explode(".",$_FILES["registration"]["name"]);
$vehicle_front = explode(".",$_FILES["vehicle_front"]["name"]);
$vehicle_rear = explode(".",$_FILES["vehicle_rear"]["name"]);


$route_file_name = $userObj->getId()."_picture_".time().".".end($route);
$registration_file_name = $userObj->getId()."_registration_".time().".".end($registration);
$vehicle_front_file_name = $userObj->getId()."_vehicle_front_".time().".".end($vehicle_front);
$vehicle_rear_file_name = $userObj->getId()."_vehicle_rear_".time().".".end($vehicle_rear);

$response = array();

// Check if image file is a actual image or fake image
if (isset($_FILES["vehicle_front"])&&isset($_FILES["vehicle_rear"])&&isset($_FILES["registration"])&&isset($_FILES["route"]))
{
    if (

        move_uploaded_file($_FILES["route"]["tmp_name"], $target_dir.$route_file_name)
        && move_uploaded_file($_FILES["registration"]["tmp_name"], $target_dir.$registration_file_name)
        && move_uploaded_file($_FILES["vehicle_front"]["tmp_name"], $target_dir.$vehicle_front_file_name)
        && move_uploaded_file($_FILES["vehicle_rear"]["tmp_name"], $target_dir.$vehicle_rear_file_name)

    )
    {

        $userObj->setDriverSteps(2);
        $userObj->setRegAlphabet($_REQUEST['reg_alphabet']);
        $userObj->setRegYear($_REQUEST['reg_year']);
        $userObj->setRegNo($_REQUEST['reg_no']);
        $userObj->setVehicleType($_REQUEST['vehicle_type']);
        $userObj->setRoute($route_file_name);
        $userObj->setRegistration($registration_file_name);
        $userObj->setVehicleFront($vehicle_front_file_name);
        $userObj->setVehicleRear($vehicle_rear_file_name);
        $userObj->updateDriverStep2();
        $userObj->setResponse("step2_completed");

    }
    else
    {
        $userObj->setResponse("error_uploading");
    }
}
else
{
    $userObj->setResponse("required_fields_missing");
}

$lr->setResponseBody(json_encode($userObj));
$lr->updateResponse();
echo json_encode($userObj);
