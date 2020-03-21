<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 3/21/20
 * Time: 2:04 PM
 */
require_once __DIR__."/../partials/header.php";
?>
    <link rel="stylesheet" href="<?php echo MY_HOST;?>/bootstrap/css/jquery.Jcrop.min.css" type="text/css" />
    <script src="<?php echo MY_HOST;?>/bootstrap/js/jquery.Jcrop.min.js"></script>

    <style>


        .bgColor {
            width: 100%;
            height: 150px;
            background-color: #fff4be;
            border-radius: 4px;
            margin-bottom: 30px;
        }

        .inputFile {
            padding: 5px;
            background-color: #FFFFFF;
            border: #F0E8E0 1px solid;
            border-radius: 4px;
        }

        .btnSubmit {
            background-color: #696969;
            padding: 5px 30px;
            border: #696969 1px solid;
            border-radius: 4px;
            color: #FFFFFF;
            margin-top: 10px;
        }

        #uploadFormLayer {
            padding: 20px;
        }

        input#crop {
            padding: 5px 25px 5px 25px;
            background: lightseagreen;
            border: #485c61 1px solid;
            color: #FFF;
            visibility: hidden;
        }

        #cropped_img {
            margin-top: 40px;
        }
    </style>


<?php
$userId= $_REQUEST['id'];
$userObj = new User();
$userObj->getUserWithId($userId);

?>




<form action="actions/update_user_information.php">

    <div class="form-group">
        <label for="InputVehicleType">Vehicle Type</label>
        <select class="form-control" id="InputVehicleType" name="vehicle_type">
            <option value="Auto" <?php if($userObj->getVehicleType()=='Auto'){echo "selected=selected";} ?>>Auto</option>
            <option value="Car" <?php if($userObj->getVehicleType()=='Car'){echo "selected=selected";} ?>>Car</option>
            <option value="Bike" <?php if($userObj->getVehicleType()=='Bike'){echo "selected=selected";} ?> >Bike</option>
        </select>
        <input type="hidden" value="<?php echo $userObj->getId(); ?>" name="id"  />
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="InputVehicleMade" placeholder="Vehicle Made" name="vehicle_made" value="<?php echo $userObj->getVehicleMade(); ?>">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="InputVehicleColor" placeholder="Vehicle Color" name="vehicle_color" value="<?php echo $userObj->getVehicleColor(); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>

</form>

<br/>
<br/>
<br/>
<br/>

<div class="clear-fix">
    <img src="<?php echo UPLOAD_URL . $userObj->getPicture() ?>" id="cropbox" class="img" /><br />
</div>

<div id="btn">
    <a class="btn btn-primary" href="actions/rotate_image.php?id=<?php echo $userObj->getId() ?>">Rotate</a>
    <a class="btn btn-primary" href="actions/compress_image.php?id=<?php echo $userObj->getId() ?>">Compress</a>


    <input type='button' class="btn btn-primary"  value='CROP' id="crop">
</div>
<div>
    <img src="#" id="cropped_img" style="display: none;">
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var size;
        $('#cropbox').Jcrop({
            aspectRatio: .75,
            onSelect: function(c){
                size = {x:c.x,y:c.y,w:c.w,h:c.h};
                $("#crop").css("visibility", "visible");
            }
        });

        $("#crop").click(function(){
            var img = $("#cropbox").attr('src');
            window.location.href='<?php echo MY_HOST;?>/admin/users/actions/image-crop.php?id=<?php echo $userId ?>&x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h;
        });
    });
</script>