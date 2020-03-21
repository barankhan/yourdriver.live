<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 3/21/20
 * Time: 2:54 PM
 */
require_once __DIR__."/../../partials/validate_session.php";



$userId= $_REQUEST['id'];
$userObj = new User();
$userObj->getUserWithId($userId);

$degrees=90;

$new_file=UPLOAD_DIR_PATH.$userObj->getPicture();

$filename = UPLOAD_URL . $userObj->getPicture();

$rotang = $degrees;
list($width, $height, $type, $attr) = getimagesize($filename);
$size = getimagesize($filename);
switch($size['mime'])
{
    case 'image/jpeg':
        $source =
            imagecreatefromjpeg($filename);
        $bgColor=imageColorAllocateAlpha($source, 0, 0,
            0, 0);
        $rotation = imagerotate($source,
            $rotang,$bgColor);
        imagealphablending($rotation, false);
        imagesavealpha($rotation, true);
        imagecreate($width,$height);
        imagejpeg($rotation,$new_file);

        break;
    case 'image/png':

        $source =
            imagecreatefrompng($filename);
        $bgColor=imageColorAllocateAlpha($source, 0, 0,
            0, 0);
        $rotation = imagerotate($source,
            $rotang,$bgColor);
        imagealphablending($rotation, false);
        imagesavealpha($rotation, true);
        imagecreate($width,$height);
        imagepng($rotation,$new_file);

        break;
    case 'image/gif':

        $source =
            imagecreatefromgif($filename);
        $bgColor=imageColorAllocateAlpha($source, 0, 0,
            0, 0);
        $rotation = imagerotate($source,
            $rotang,$bgColor);
        imagealphablending($rotation, false);
        imagesavealpha($rotation, true);
        imagecreate($width,$height);
        imagegif($rotation,$new_file);

        break;
    case 'image/vnd.wap.wbmp':
        $source =
            imagecreatefromwbmp($filename);
        $bgColor=imageColorAllocateAlpha($source, 0, 0,
            0, 0);
        $rotation = imagerotate($source,
            $rotang,$bgColor);
        imagealphablending($rotation, false);
        imagesavealpha($rotation, true);
        imagecreate($width,$height);
        imagewbmp($rotation,$new_file);

        break;
}


header("Location: ".MY_HOST."/admin/users/update_driver_information.php?id=".$userObj->getId());
