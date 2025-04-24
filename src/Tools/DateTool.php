<?php

namespace App\Tools;

use App\Entity\Holiday;
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

    static function checkDatesOverlap(Holiday $holidays1, Holiday $holidays2): ?int
    {
        $overlapStart = max($holidays1->getDateStart(), $holidays2->getDateStart());
        $overlapEnd = min($holidays1->getDateEnd(), $holidays2->getDateEnd());

        if ($overlapEnd < $overlapStart) {
            return null; // No overlap
        }

        // Add one day to make it inclusive
        return $overlapStart->diff($overlapEnd)->days + 1;
    }
}
