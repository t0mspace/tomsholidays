<?php

namespace App\Tools;

use DateInterval;
use DatePeriod;

class DateTool
{
    /**
     * @throws \DateMalformedPeriodStringException
     */
    static function getDatesFromRange(\DateTimeImmutable $start, \DateTimeImmutable $end, string $format = 'Y-m-d'): array {

        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');
        $end->add($interval);
        $period = new DatePeriod($start, $interval, $end);

        // Use loop to store date into array
        foreach($period as $date){
            $array[] = $date->format($format);

        }

        // Return the array elements
        return $array;

    }
}
