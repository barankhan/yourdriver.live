<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 3/21/20
 * Time: 3:16 PM
 */

require_once __DIR__."/../../partials/validate_session.php";



$userId= $_REQUEST['id'];
$userObj = new User();
$userObj->getUserWithId($userId);


function compress_image($source_url, $destination_url, $quality) {

    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}


$picture = explode(".",$userObj->getPicture());
$new_name= $userId."_resize".time().".".end($picture);

$new_file=UPLOAD_DIR_PATH.$new_name;

$filename = UPLOAD_URL . $userObj->getPicture();

$rotang = $degrees;
list($width, $height, $type, $attr) = getimagesize($filename);
$size = getimagesize($filename);


if($size['mime']=='image/jpeg' ||$size['mime']='image/png' || $size['mime']='image/gif' ){
    if(resizeImage($filename, $new_file,200,200)){
        $userObj->setPicture($new_name);
        $userObj->update();
    };
}




function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality = 80)
{
    // Obtain image from given source file.
    $info = getimagesize($sourceImage);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($sourceImage);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($sourceImage);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($sourceImage);
    else
        return false;

    // Get dimensions of source image.
    list($origWidth, $origHeight) = getimagesize($sourceImage);

    if ($maxWidth == 0)
    {
        $maxWidth  = $origWidth;
    }

    if ($maxHeight == 0)
    {
        $maxHeight = $origHeight;
    }

    // Calculate ratio of desired maximum sizes and original sizes.
    $widthRatio = $maxWidth / $origWidth;
    $heightRatio = $maxHeight / $origHeight;

    // Ratio used for calculating new image dimensions.
    $ratio = min($widthRatio, $heightRatio);

    // Calculate new image dimensions.
    $newWidth  = (int)$origWidth  * $ratio;
    $newHeight = (int)$origHeight * $ratio;

    // Create final image with new dimensions.
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    imagejpeg($newImage, $targetImage, $quality);

    // Free up the memory.
    imagedestroy($image);
    imagedestroy($newImage);

    return true;
}






header("Location: ".MY_HOST."/admin/users/update_driver_information.php?id=".$userObj->getId());
