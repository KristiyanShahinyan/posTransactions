<?php

namespace App\Util;

use DateTime;
use DatePeriod;
use DateInterval;

/**
 * Helper for the daily analysis report.
 *
 * All transactions for a single day aggregated on a hourly basis.
 */
class DailyAnalyticsIntervalHelper extends AnalyticsIntervalHelperAbstract implements AnalyticsIntervalHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHistogramInterval(): string
    {
        return 'hour';
    }

    /**
     * {@inheritdoc}
     */
    public function getHistogramDateFormat(): string
    {
        return 'HH';
    }

    /**
     * {@inheritdoc}
     */
    public function getBucketIndexByDate(DateTime $intervalStart): string
    {
        return $intervalStart->format('H');
    }

    /**
     * {@inheritdoc}
     */
    public function getAnalyzedPeriod(DateTime $pivotTime): DatePeriod
    {
        $start = new DateTime($pivotTime->format('Y-m-d 00:00:00'));
        $end = new DateTime($pivotTime->format('Y-m-d 23:59:59'));
        $interval = new DateInterval('PT1H');

        return new DatePeriod($start, $interval, $end);
    }

    public function getStatsPeriod(DateTime $endDate, DateTime $startDate = null): DatePeriod
    {
        if (!$startDate){
            $startDate = (clone $endDate)->sub(new DateInterval('P1D'));
        }
        $interval = new DateInterval('PT1H');
        
        return new DatePeriod($startDate, $interval, $endDate);
    }

}
