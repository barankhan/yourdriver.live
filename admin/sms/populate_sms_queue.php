<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 7/20/20
 * Time: 12:53 PM
 */
require_once __DIR__."/../partials/header.php";

if(isset($_POST['submit'])){

    $selectedCityCount = count($_POST['city']);
    $selectedCity = "";
    $i = 0;
    while ($i < $selectedCityCount) {
        $selectedCity .=  "'" . $_POST['city'][$i] . "'";
        if ($i < $selectedCityCount - 1) {
            $selectedCity = $selectedCity . ", ";
        }
        $i ++;
    }


    $selectedVehicleCount = count($_POST['vehicle']);
    $selectedVehicle = "";
    $i = 0;
    while ($i < $selectedVehicleCount) {
        $selectedVehicle .=  "'" . $_POST['vehicle'][$i] . "'";
        if ($i < $selectedVehicleCount - 1) {
            $selectedVehicle = $selectedVehicle . ", ";
        }
        $i ++;
    }


    $online_driver_check = '';

    if($_POST['include_online']=='yes'){
        $online_driver_check = ' and is_driver_online<>1 ';
    }else{
        $online_driver_check = '  ';
    }



    $userObj = new User();
    $users = $userObj->getAllDrivers(" and city in ($selectedCity) and vehicle_type in ($selectedVehicle) $online_driver_check",1,10000 );


    foreach ($users as $user){
        $newUserObj = new User();
        $newUserObj->setAllFields($user);
        $msg = str_replace("{name}",$newUserObj->getName(),$_POST['message']);
        $smsQueueObj = new SMSQueue();
        $smsQueueObj->setNumber($newUserObj->getMobile());
        $smsQueueObj->setMessage(trim($msg));
        $smsQueueObj->setSendBy($_POST['device']);
        $smsQueueObj->insert();
    }
}

?>





<h2> Send SMS To Drivers:</h2>

<form method="post">
    <div class="form-group">

        <div class="form-group">
            <label for="deviceSelect">SMS Devices</label>
            <select class="form-control" id="deviceSelect" name="device" required="required">
                <option value="5">Driver Mobilink</option>
            </select>
        </div>
        <div class="form-group">
            <label for="citySelect">Select Cities</label>
            <select multiple class="form-control" id="citySelect" name="city[]" required="required">
                <option>multan</option>
                <option>Bahawalpur</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <label for="vehicleSelect" >Select Vehicle Types</label>
            <select multiple class="form-control" id="vehicleSelect" name="vehicle[]" required="required">
                <option>Auto</option>
                <option>Bike</option>
                <option>Car</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1" >Message</label>
        <textarea minlength="10" maxlength="140" class="form-control" id="exampleInputPassword1" placeholder="Message" name="message"  onkeyup="countChar(this)" required="required">
Dear {name}, Shukriya, Driver App.
        </textarea>
        <small id="messageCharCount" class="form-text text-muted"></small>
        <small id="shortCodes" class="form-text text-muted">Short Code like {name}</small>

    </div>

    <div class="form-group">
        <label for="" >Exclude Online</label>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="include_online" id="inlineRadio1" value="yes">
        <label class="form-check-label" for="inlineRadio1">yes</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="include_online" id="inlineRadio2" value="no" checked="checked">
        <label class="form-check-label" for="inlineRadio2">no</label>
    </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </div>
</form>

<script>
    function countChar(val) {
        var len = val.value.length;
        if (len >= 160) {
            val.value = val.value.substring(0, 159);
        } else {
            $('#messageCharCount').text(159 - len);
        }
    };
</script>