<?php
require_once __DIR__."/../../../vendor/autoload.php";

  $img_r = imagecreatefromjpeg($_GET['img']);
  $dst_r = ImageCreateTrueColor( $_GET['w'], $_GET['h'] );
 






$userId= $_REQUEST['id'];
$userObj = new User();
$userObj->getUserWithId($userId);

$degrees=90;
$picture = explode(".",$userObj->getPicture());
$new_name= $userId."_cropped_".time().".".end($picture);
$new_file=UPLOAD_DIR_PATH.$new_name;

$filename = UPLOAD_URL . $userObj->getPicture();

$rotang = $degrees;
list($width, $height, $type, $attr) = getimagesize($filename);
$size = getimagesize($filename);
switch($size['mime'])
{
    case 'image/jpeg':
        $source =
            imagecreatefromjpeg($filename);
        imagecopyresampled($dst_r, $source, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);

        imagejpeg($dst_r,$new_file);

        break;
    case 'image/png':

        $source =
            imagecreatefrompng($filename);
        imagecopyresampled($dst_r, $source, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);
        imagepng($dst_r,$new_file);

        break;
    case 'image/gif':

        $source =
            imagecreatefromgif($filename);
        imagecopyresampled($dst_r, $source, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);
        imagegif($dst_r,$new_file);

        break;
    case 'image/vnd.wap.wbmp':
        $source =
            imagecreatefromwbmp($filename);
        imagecopyresampled($dst_r, $source, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);
        imagewbmp($dst_r,$new_file);

        break;
}

$userObj->setPicture($new_name);
$userObj->update();


header("Location: ".MY_HOST."/admin/users/update_driver_information.php?id=".$userObj->getId());
