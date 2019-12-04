<?php
require_once __DIR__."/../../model/user.php";
require_once __DIR__."/../../model/LogRequest.php";

$target_dir = __DIR__."/../../uploads/";
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST).json_encode($_FILES));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($_REQUEST['mobile']);
$lr->insertLog();

$userObj = new User();
$userObj->getUserWithMobile($_REQUEST['mobile']);

$picture = explode(".",$_FILES["picture"]["name"]);
$licence = explode(".",$_FILES["licence"]["name"]);
$cnic_front = explode(".",$_FILES["cnic_front"]["name"]);
$cnic_rear = explode(".",$_FILES["cnic_rear"]["name"]);

$pic_file_name = $userObj->getId()."_picture_".time().".".end($picture);
$licence_file_name = $userObj->getId()."_licence_".time().".".end($licence);
$cnic_front_file_name = $userObj->getId()."_cnic_front_".time().".".end($cnic_front);
$cnic_rear_file_name = $userObj->getId()."_cnic_rear_".time().".".end($cnic_rear);
$response = array();

// Check if image file is a actual image or fake image
if (isset($_FILES["cnic_front"])&&isset($_FILES["cnic_rear"])&&isset($_FILES["picture"]))
{
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_dir.$pic_file_name)
        && move_uploaded_file($_FILES["licence"]["tmp_name"], $target_dir.$licence_file_name)
        && move_uploaded_file($_FILES["cnic_front"]["tmp_name"], $target_dir.$cnic_front_file_name)
        && move_uploaded_file($_FILES["cnic_rear"]["tmp_name"], $target_dir.$cnic_rear_file_name)

    )
    {

        $userObj->setPicture($pic_file_name);
        $userObj->setLicence($licence_file_name);
        $userObj->setCnicFront($cnic_front_file_name);
        $userObj->setCnicRear($cnic_rear_file_name);
        $userObj->setCnic($_REQUEST['cnic']);
        $userObj->setName($_REQUEST['name']);
        $userObj->setFather($_REQUEST['father']);
        $userObj->setDriverSteps(1);
        $userObj->setResponse("step1_completed");
        $userObj->updateDriverStep1();
    }
    else
    {
        $userObj->setResponse("error_uploading");;
    }
}
else
{
    $userObj->setResponse("required_fields_missing");
}

$lr->setResponseBody(json_encode($userObj));
$lr->updateResponse();
echo json_encode($userObj);