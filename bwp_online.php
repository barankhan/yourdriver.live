<?php
require_once __DIR__."/vendor/autoload.php";
// Include config file
       $userObj =  new User();
$drivers =  $userObj->getAllDrivers(" and city='Bahawalpur' and is_driver_online=1" ,1,100);

?>

    <!DOCTYPE html>
    <html>
<head>
    <link rel="stylesheet" href="<?php echo MY_HOST;?>/bootstrap/css/bootstrap.min.css">

</head>
<body>
<div class="container">
    <table class="table table-striped">
        <thead>
        <tr    >

            <td >
                Name
            </td>
            <td >
                Vehicle Type
            </td>

            <td >
                Vehicle Registration
            </td>
            <td >
                Online At
            </td>
            <td >
                City
            </td>
            <td >
                Actions
            </td>


        </tr>
        </thead>
        <tbody>



        <?php
        $i=0;
        foreach ($drivers as $driver){

            $i++;
            $userObj->setAllFields($driver);
            ?>
            <tr  >
                <td >
                    <?php echo $userObj->getName(); ?>
                </td>
                <td >
                    <?php echo $userObj->getVehicleType(); ?>
                </td>

                <td >
                    <?php echo $userObj->getRegAlphabet()."-".$userObj->getRegYear()."-".$userObj->getRegNo();?>
                </td>
                <td >
                    <?php echo $userObj->getOnlineAt();?>
                </td>
                <td >
                    <?php echo $userObj->getCity();?>
                </td>
                <td >
                    <?php echo "<a target='_blank' href='driver_current_location.php?id=".$userObj->getId()."' class='btn btn-primary'>Current Location</a>"; ?>
                </td>
            </tr>

            <?php
        }



        echo "Total Online Drivers: ".$i;
        ?>

        </tbody>
    </table>
</div>
</body>
