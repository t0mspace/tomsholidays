<?php

namespace App\Tools;

use DateInterval;
use DatePeriod;

class DateTool
{
    /**
     * @throws \DateMalformedPeriodStringException
     * @throws \DateMalformedIntervalStringException
     */
    static function getDatesFromRange(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
        string $intervalSpec = 'P1D',
        string $format = 'Y-m-d'
    ): array {
        $array = [];
        $interval = new \DateInterval($intervalSpec);
        $endPlus = $end->add($interval);
        $period = new \DatePeriod($start, $interval, $endPlus);

        foreach ($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }
}
