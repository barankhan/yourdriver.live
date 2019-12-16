<?php
require_once __DIR__. "/../model/DriverTransaction.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/16/19
 * Time: 11:24 AM
 */
class Misc
{
    public static function generateCancelledTransaction($rideObj,$basePrice){
        $tranObj = new DriverTransaction();
        $tranObj->setRideId($rideObj->getId());
        $tranObj->setPassengerId($rideObj->getPassengerId());
        $tranObj->setDriverId($rideObj->getDriverId());
        $tranObj->setTransactionType("cancelled_ride");
        $tranObj->setDriverStartUpFare(array_key_exists ("driver_start_up_fare" ,  $basePrice )?$basePrice["driver_start_up_fare"]:0);
        $tranObj->setCompanyServiceCharges(array_key_exists ("company_service_charges" ,  $basePrice )?$basePrice["company_service_charges"]:0);
        $tranObj->setTimeElapsedRate(array_key_exists ("time_elapsed_rate" ,  $basePrice )?$basePrice["time_elapsed_rate"]:0);
        $tranObj->setKmTravelledRate(array_key_exists ("km_travelled_rate" ,  $basePrice )?$basePrice["km_travelled_rate"]:0);
        $tranObj->setTotalFare();
        $tranObj->setTotalAmount();
        $tranObj->insertCanceledRide();
        return $tranObj;
    }

}