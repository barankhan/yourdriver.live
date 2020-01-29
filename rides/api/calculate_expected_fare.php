<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/24/20
 * Time: 4:59 PM
 */
require_once __DIR__."/../../vendor/autoload.php";

$mobile= $_REQUEST['mobile'];
$lr = new LogRequest();
$lr->setRequestUri($_SERVER['REQUEST_URI']);
$lr->setRequestBody(json_encode($_REQUEST));
$lr->setRequestHeader(json_encode($_SERVER));
$lr->setMobileNumber($mobile);
$lr->insertLog();


$city = findRideCity::getCity( $_REQUEST['origin_lat'],$_REQUEST['origin_lng']);

$driver_types = array("Auto","Car","Bike");

if($city=='multan'){
    $origin      = $_REQUEST['origin_lat'].",".$_REQUEST['origin_lng'];
    $destination      = $_REQUEST['destination_lat'].",".$_REQUEST['destination_lng'];
    $key = "AIzaSyCCqsVyBepTZWwra2IdMy0o-6Hrp0ZJY_E";
    $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".urlencode($origin)."&destination=" . urlencode( $destination) . "&sensor=false&key=" . $key;
    $jsonfile = file_get_contents($url);
    $jsondata = json_decode($jsonfile);

    $distance_in_meters = $jsondata->routes[0]->legs[0]->distance->value;
    $duration_in_seconds = $jsondata->routes[0]->legs[0]->duration->value;
    $duration_title = $jsondata->routes[0]->legs[0]->duration->text;

    $vehicle_types=array();
    foreach($driver_types as $type){
      $basePrice =   basePrice::getBasePrice($type,$_REQUEST['origin_lat'],$_REQUEST['origin_lng']);
      $exp_fare = round($basePrice["driver_start_up_fare"]+$basePrice["company_service_charges"]+
          ($basePrice['km_travelled_rate']*($distance_in_meters/1000))+
          ($basePrice['time_elapsed_rate']*($duration_in_seconds/60)));

        $exp_fare2  = round($basePrice["driver_start_up_fare"]+$basePrice["company_service_charges"]+
            ($basePrice['km_travelled_rate']*($distance_in_meters/1000)*1.2)+
            ($basePrice['time_elapsed_rate']*($duration_in_seconds/60)*1.3));
        if($type=='Auto'){
            $vehicle_types[] = array("title"=>$type,"expectedFare"=>$exp_fare."-".$exp_fare2);
        }else{
            $vehicle_types[] = array("title"=>$type,"expectedFare"=>'N/A');
        }

    }
    $res = array("response"=>'success',"vehicleTypesList"=>$vehicle_types,"duration"=>$duration_title);

}else{
    $res = array("response"=>'error');
}

$var = json_encode($res);
$lr->setResponseBody($var);
$lr->updateResponse();
echo $var;







