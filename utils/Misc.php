<?php
require_once __DIR__."/../vendor/autoload.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/16/19
 * Time: 11:24 AM
 */
class Misc
{
    public static function generateCancelledTransaction(ride $rideObj,$basePrice,User &$passengerObj=null,User &$driverObj=null){
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

        if($tranObj->getTotalFare()>0){
            // If there is cancelled amount and user has balance. Deduct from the User Account and add into Driver Account.

            if($passengerObj->getBalance()>=$tranObj->getTotalFare()){
                // if user fare is less or equal to his balance dedcut it.


                $tranObj->setDriverInitialBalance($driverObj->getBalance());
                $tranObj->setPassengerInitialBalance($passengerObj->getBalance());
                $tranObj->setTransactionCompleted(1);

                $tranObj->setCompanyOutwardHead('Balance_Used');
                $passengerObj->setBalance($passengerObj->getBalance()-$tranObj->getTotalFare());
                $tranObj->setOutwardHeadAmount($tranObj->getTotalFare());
                $driverObj->setBalance($driverObj->getBalance()+($tranObj->getTotalFare()-$tranObj->getCompanyServiceCharges()));

                $driverObj->update();
                $passengerObj->update();

            }else{
                $passengerObj->setBalance($passengerObj->getBalance()-$tranObj->getTotalFare());
                $passengerObj->update();
            }
        }else{
            $tranObj->setTransactionCompleted(1);
        }
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

        $tranObj->setDriverInitialBalance($driverObj->getBalance());
        $tranObj->setPassengerInitialBalance($passengerObj->getBalance());

        $start = strtotime($rideObj->getRideStartedAt());
        $end = strtotime($rideObj->getRideEndedAt());
        $mins = ($end - $start) / 60;
        $tranObj->setTimeElapsedMinutes($mins);
        $tranObj->setTotalFare();

        $driverObj->setBalance($driverObj->getBalance() - $tranObj->getCompanyServiceCharges());

        if($passengerObj->getBalance()>0){
            // Wallet already have amount.
            $tranObj->setCompanyOutwardHead('Balance_Used');

            if($passengerObj->getBalance()>=$tranObj->getTotalFare()){
                $tranObj->setPayableAmount(0);
                $driverObj->setBalance($driverObj->getBalance()+($tranObj->getTotalFare()));
                $passengerObj->setBalance($passengerObj->getBalance()-$tranObj->getTotalFare());
                $tranObj->setOutwardHeadAmount($tranObj->getTotalFare());
            }else{
                $tranObj->setPayableAmount($tranObj->getTotalFare()-$passengerObj->getBalance());
                $driverObj->setBalance($driverObj->getBalance()+($tranObj->getTotalFare()-$tranObj->getPayableAmount()));
                $tranObj->setOutwardHeadAmount($passengerObj->getBalance());
                $passengerObj->setBalance(0);
            }
            $passengerObj->update();
        }elseif ($passengerObj->getBalance()<0){
           // Cancelled Amount.

            $tranObj->setIsCancelAdjustment(1);
            $canceled_transactions = $tranObj->getPassengerCanceledUnpaidTransactions();
            foreach($canceled_transactions as  $canceled_transaction){
                $cTObj = new DriverTransaction();
                $cTObj->setAllFields($canceled_transaction);

            }



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