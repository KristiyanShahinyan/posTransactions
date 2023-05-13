<?php

namespace App\Util;

use DateTime;
use DatePeriod;
use DateInterval;

/**
 * Helper for the monthly analysis report.
 *
 * All transactions for a single month aggregated on a daily basis.
 */
class MonthlyAnalyticsIntervalHelper extends AnalyticsIntervalHelperAbstract implements AnalyticsIntervalHelperInterface
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
        return 'yyyy-MM-dd';
    }

    /**
     * {@inheritdoc}
     */
    public function getAnalyzedPeriod(DateTime $pivotTime): DatePeriod
    {
        $start = $this->getMonthStart($pivotTime);
        $end = (clone $start)->add(new DateInterval('P1M'))->sub(new DateInterval('PT1S'));
        $interval = new DateInterval('P1D');

        return new DatePeriod($start, $interval, $end);
    }

    public function getStatsPeriod(DateTime $endDate, DateTime $startDate = null): DatePeriod
    {
        if (!$startDate){
            $startDate = (clone $endDate)->sub(new DateInterval('P1M'))->add(new DateInterval('P1D'));
        }
        
        $endDate = new DateTime($endDate->format('Y-m-d 23:59:00'));
        
        $interval = new DateInterval('P1D');
        
        return new DatePeriod($startDate, $interval, $endDate);
    }
   
    /**
     * {@inheritdoc}
     */
    public function getBucketIndexByDate(\DateTime $intervalStart): string
    {
        return $intervalStart->format('Y-m-d');
    }
}
