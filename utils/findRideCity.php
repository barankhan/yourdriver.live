<?php

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/12/19
 * Time: 4:11 PM
 */
class findRideCity
{
    private static $cities = array("multan"=>array(30.0519792,71.3039295,30.3105332,71.64538709999999),"Bahwalpur"=>array(29.3088215, 71.5971945,29.44505909999999, 71.7828297));
    public static function getCity($pickUpLat,$pickUpLng){
        foreach(self::$cities as $key=>$city){
            if(self::inBounds($pickUpLat,$pickUpLng,$city[2],$city[3],$city[0],$city[1])){
//            if(self::inBounds($pickUpLat,$pickUpLng,$city[0],$city[1],$city[2],$city[3])){
                return $key;
            }
        }
        return "invalid_city";
    }

    static function  inBounds($pointLat, $pointLong, $boundsNElat, $boundsNElong, $boundsSWlat, $boundsSWlong) {
        $eastBound = $pointLong < $boundsNElong;
        $westBound = $pointLong > $boundsSWlong;

        if ($boundsNElong < $boundsSWlong) {
            $inLong = $eastBound || $westBound;
        } else {
            $inLong = $eastBound && $westBound;
        }

        $inLat = $pointLat > $boundsSWlat && $pointLat < $boundsNElat;
        return $inLat && $inLong;
    }

}