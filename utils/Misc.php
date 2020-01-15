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
        $tranObj->insert();
        return $tranObj;
    }





    public static function generateCompletedRideTransaction(ride $rideObj,$basePrice,User $passengerObj,User &$driverObj){
        $tranObj = new DriverTransaction();
        $tranObj->setRideId($rideObj->getId());
        $tranObj->setPassengerId($rideObj->getPassengerId());
        $tranObj->setDriverId($rideObj->getDriverId());
        $tranObj->setTransactionType("completed_ride");
        $tranObj->setDriverStartUpFare(array_key_exists ("driver_start_up_fare" ,  $basePrice )?$basePrice["driver_start_up_fare"]:0);
        $tranObj->setCompanyServiceCharges(array_key_exists ("company_service_charges" ,  $basePrice )?$basePrice["company_service_charges"]:0);
        $tranObj->setTimeElapsedRate(array_key_exists ("time_elapsed_rate" ,  $basePrice )?$basePrice["time_elapsed_rate"]:0);
        $tranObj->setKmTravelledRate(array_key_exists ("km_travelled_rate" ,  $basePrice )?$basePrice["km_travelled_rate"]:0);
        $tranObj->setKmTravelled($rideObj->getDistance()/1000);

        $start = strtotime($rideObj->getRideStartedAt());
        $end = strtotime($rideObj->getRideEndedAt());
        $mins = ($end - $start) / 60;
        $tranObj->setTimeElapsedMinutes($mins);
        $tranObj->setTotalFare();

        $driverObj->setBalance($driverObj->getBalance() - $tranObj->getCompanyServiceCharges());

        if($passengerObj->getBalance()>0){
            // Wallet already have amount.
            $tranObj->setCompanyHead('Balance_Used');
            $tranObj->setHeadAmount($tranObj->getTotalFare());

            if($passengerObj->getBalance()>=$tranObj->getTotalFare()){
                $tranObj->setPayableAmount(0);
                $driverObj->setBalance($driverObj->getBalance()+($tranObj->getTotalFare()-$tranObj->getCompanyServiceCharges()));
                $passengerObj->setBalance($passengerObj->getBalance()-$tranObj->getTotalFare());
            }else{
                $tranObj->setPayableAmount($tranObj->getTotalFare()-$passengerObj->getBalance());
                $driverObj->setBalance($driverObj->getBalance()-($tranObj->getTotalFare()-$tranObj->getCompanyServiceCharges()-$tranObj->getPayableAmount()));
                $passengerObj->setBalance(0);
            }


            $passengerObj->update();


        }elseif ($passengerObj->getBalance()<0){
           // Cancelled Amount.
            $tranObj->setPayableAmount($tranObj->getTotalFare());
        }else{
            $tranObj->setPayableAmount($tranObj->getTotalFare());
        }


        $driverObj->update();
        $passengerObj->update();



        $tranObj->setTotalAmount();
        $tranObj->insert();
        return $tranObj;
    }


}