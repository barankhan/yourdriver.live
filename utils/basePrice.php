<?php
require_once __DIR__."/findRideCity.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/12/19
 * Time: 4:28 PM
 */
class basePrice
{
    public static function getBasePrice($vehicle_type,$pickupLat,$pickLng){
        switch (strtolower($vehicle_type)){

            case "auto":
                switch (findRideCity::getCity($pickupLat,$pickLng)){
                    case "multan":
                        return array("driver_start_up_fare"=>35,"company_service_charges"=>0,"time_elapsed_rate"=>3,"km_travelled_rate"=>6);
                        break;
                    default:
                        return array("driver_start_up_fare"=>35,"company_service_charges"=>0,"time_elapsed_rate"=>3,"km_travelled_rate"=>6);
                }
            case "car":
                switch (findRideCity::getCity($pickupLat,$pickLng)){
                    case "multan":
                        return array("driver_start_up_fare"=>65,"company_service_charges"=>0,"time_elapsed_rate"=>3.5,"km_travelled_rate"=>7);
                        break;
                    default:
                        return array("driver_start_up_fare"=>65,"company_service_charges"=>0,"time_elapsed_rate"=>3.5,"km_travelled_rate"=>7);
                }
            case "bike":
                switch (findRideCity::getCity($pickupLat,$pickLng)){
                    case "multan":
                        return array("driver_start_up_fare"=>20,"company_service_charges"=>0,"time_elapsed_rate"=>2,"km_travelled_rate"=>5);
                        break;
                    default:
                        return array("driver_start_up_fare"=>20,"company_service_charges"=>0,"time_elapsed_rate"=>2,"km_travelled_rate"=>5);
                }
            default:
                return array("driver_start_up_fare"=>30,"company_service_charges"=>20,"time_elapsed_rate"=>4,"km_travelled_rate"=>5);
        }

    }
}