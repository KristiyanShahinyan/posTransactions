<?php

namespace App\Util;

use DateTime;
use DatePeriod;
use DateInterval;

/**
 * Helper for the weekly analysis report.
 *
 * All transactions for a single week aggregated on a daily basis.
 */
class WeeklyAnalyticsIntervalHelper extends AnalyticsIntervalHelperAbstract implements AnalyticsIntervalHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHistogramInterval(): string
    {
        return 'day';
    }

    /**
     * {@inheritdoc}
     */
    public function getHistogramDateFormat(): string
    {
        return 'E'; // Day of week - e.g. "Mon"
    }

    /**
     * {@inheritdoc}
     */
    public function getAnalyzedPeriod(DateTime $pivotTime): DatePeriod
    {
        $start = new DateTime($pivotTime->format('Y-m-d 00:00:00'));
        $end = (clone $start)->add(new DateInterval('P7D'))->sub(new DateInterval('PT1S'));
        $interval = new DateInterval('P1D');

        return new DatePeriod($start, $interval, $end);
    }

    public function getStatsPeriod(DateTime $endDate, DateTime $startDate = null): DatePeriod
    {
        if (!$startDate){
            $startDate = (clone $endDate)->sub(new DateInterval('P7D'));
        }
        
        $endDate = new DateTime($endDate->format('Y-m-d 23:59:00'));
        
        $interval = new DateInterval('P1D');
        
        return new DatePeriod($startDate, $interval, $endDate);
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function getBucketIndexByDate(DateTime $intervalStart): string
    {
        return $intervalStart->format('D');
    }
}
