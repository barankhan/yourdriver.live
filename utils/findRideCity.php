<?php

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/12/19
 * Time: 4:11 PM
 */
class findRideCity
{
    private static $cities = array("multan"=>array(30.0519792,71.3039295,30.3105332,71.64538709999999));
    public static function getCity($pickUpLat,$pickUpLng){
        foreach(self::$cities as $key=>$city){
            if(self::inBounds($pickUpLat,$pickUpLng,$city[2],$city[3],$city[0],$city[1])){
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