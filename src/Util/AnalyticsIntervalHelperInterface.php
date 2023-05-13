<?php

namespace App\Util;

/**
 * Interface of a aggregation data/period helper.
 */
interface AnalyticsIntervalHelperInterface
{
    /**
     * Returns the interval on which the histogram will aggregate the data.
     */
    public function getHistogramInterval(): string;

    /**
     * Returns the date format to be used by the histogram aggregation.
     */
    public function getHistogramDateFormat(): string;

    /**
     * Returns the timezone according to which the histogram will aggregate the data.
     */
    public function getFormattedTimezone(\DateTime $pivotTime): string;

    /**
     * Returns the time boundaries of the analyzed data.
     */
    public function getAnalyzedPeriod(\DateTime $pivotTime): \DatePeriod;

    /**
     * Returns the time boundaries of the analyzed data.
     */
    public function getStatsPeriod(\DateTime $endDate, \DateTime $startDate): \DatePeriod;

    /**
     * Returns the formatted key to be used for bucket's data.
     */
    public function getFormattedIntervalIndex(array $aggregationBucket): string;

    /**
     * Returns the bucket corresponding to the provided interval starting time.
     */
    public function getBucketIndexByDate(\DateTime $intervalStart): string;
}
