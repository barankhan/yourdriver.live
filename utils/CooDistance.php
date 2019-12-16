<?php

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/16/19
 * Time: 10:45 AM
 */
class CooDistance
{
    public static function
    calculateDistanceBetweenTwoPoints($latitudeOne = '', $longitudeOne = '', $latitudeTwo = '', $longitudeTwo = '', $distanceUnit = '', $round = false, $decimalPoints = '')
    {
        if (empty($decimalPoints)) {
            $decimalPoints = '3';
        }
        if (empty($distanceUnit)) {
            $distanceUnit = 'KM';
        }
        $distanceUnit = strtolower($distanceUnit);
        $pointDifference = $longitudeOne - $longitudeTwo;
        $toSin = (sin(deg2rad($latitudeOne)) * sin(deg2rad($latitudeTwo))) + (cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * cos(deg2rad($pointDifference)));
        $toAcos = acos($toSin);
        $toRad2Deg = rad2deg($toAcos);

        $toMiles = $toRad2Deg * 60 * 1.1515;
        $toKilometers = $toMiles * 1.609344;
        $toNauticalMiles = $toMiles * 0.8684;
        $toMeters = $toKilometers * 1000;
        $toFeets = $toMiles * 5280;
        $toYards = $toFeets / 3;


        switch (strtoupper($distanceUnit)) {
            case 'ML'://miles
                $toMiles = ($round == true ? round($toMiles) : round($toMiles, $decimalPoints));
                return $toMiles;
                break;
            case 'KM'://Kilometers
                $toKilometers = ($round == true ? round($toKilometers) : round($toKilometers, $decimalPoints));
                return $toKilometers;
                break;
            case 'MT'://Meters
                $toMeters = ($round == true ? round($toMeters) : round($toMeters, $decimalPoints));
                return $toMeters;
                break;
            case 'FT'://feets
                $toFeets = ($round == true ? round($toFeets) : round($toFeets, $decimalPoints));
                return $toFeets;
                break;
            case 'YD'://yards
                $toYards = ($round == true ? round($toYards) : round($toYards, $decimalPoints));
                return $toYards;
                break;
            case 'NM'://Nautical miles
                $toNauticalMiles = ($round == true ? round($toNauticalMiles) : round($toNauticalMiles, $decimalPoints));
                return $toNauticalMiles;
                break;
        }


    }

}