<?php
require_once __DIR__."/../../vendor/autoload.php";

$rideObj = new ride();
$rides = $rideObj->getNonAssignedRides();

$userObj =  new User();

foreach($rides as $ride) {
    $newRideObj = new ride();
    $newRideObj->setAllFields($ride);

    $testRideObj = new ride();
    $testRideObj->setId($newRideObj->getId());
    $testRideObj->findRideWithId();


    if($testRideObj->getIsRideCancelled()!=1 and $testRideObj->getDriverId()==0) {


        $drivers = $userObj->getAvailableDrivers($newRideObj->getPickupLat(), $newRideObj->getPickupLng(), $newRideObj->getVehicleType(), 1, 4, true, $newRideObj->getId());

        if (array_key_exists(0, $drivers)) {
            $driver = new User();
            $driver->setAllFields($drivers[0]);
            $driver->setAcceptancePoints($driver->getAcceptancePoints() - 5);
            $driver->update();

            $rideAlertObj = new rideAlert();
            $rideAlertObj->setDriverId($driver->getId());
            $rideAlertObj->setRideId($newRideObj->getId());
            $rideAlertObj->setDriverLat($driver->getLat());
            $rideAlertObj->setDriverLng($driver->getLng());
            $rideAlertObj->insert();
            // now send push notification to the driver.


            $newRideObj->setResponse("alert_sent_to_driver");
            $newRideObj->setMessage("waiting for driver to accept the ride");
            $var = json_encode($newRideObj);

            $fbaseObj = new firebaseNotification();

            $notification['title'] = 'Ride Alert';
            $notification['body'] = 'Passenger is waiting for you.';
            $payload['do'] = "ride_alert";
            $payload['msg'] = "You have a new ride";
            $payload['key'] = "ride_alert";
            $payload['lat'] = "" . $newRideObj->getPickupLat();
            $payload['lng'] = "" . $newRideObj->getPickupLng();
            $payload['ride_id'] = "" . $newRideObj->getId();

            $token = $driver->getFirebaseToken();

            $fabseRes = $fbaseObj->sendPayloadOnly(0, $token, $payload, null, 'high');

        } else {
            $newRideAlertObj = new rideAlert();
            if($newRideAlertObj->shouldCancelRideInCron($newRideObj->getId())) {
                $fbaseObj = new firebaseNotification();
                $payload['message'] = "Sorry! Driver has not responded to your pickup alert. Please try again!";
                $payload['key'] = "p_ride_cancelled";
                $passengerObj = new User();
                $passengerObj->getUserWithId($newRideObj->getPassengerId());
                $payload['user'] = json_encode($passengerObj);
                $token = $passengerObj->getFirebaseToken();
                $fabseRes = $fbaseObj->sendPayloadOnly(0, $token, $payload, null, 'high', 90);
                $newRideObj->setIsRideCancelled(1);
                $newRideObj->update();

            }
        }
    }
}
echo "completed";















