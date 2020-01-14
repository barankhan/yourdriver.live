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

$pic_file_name = $userObj->getId()."_picture_".time().".".end($picture);
$response = array();

// Check if image file is a actual image or fake image
if (isset($_FILES["picture"]))
{
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_dir.$pic_file_name))
    {

        $userObj->setPicture($pic_file_name);
        $userObj->update();
        $userObj->setResponse("uploaded");
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