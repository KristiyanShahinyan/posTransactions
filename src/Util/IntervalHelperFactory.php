<?php

namespace App\Util;

use App\Dto\Request\AnalyticsDto;

/**
 * An interval helper factory.
 */
final class IntervalHelperFactory
{
    public function create(string $type): AnalyticsIntervalHelperInterface
    {
        switch ($type) {
            case AnalyticsDto::PERIOD_MONTHLY:
                return new MonthlyAnalyticsIntervalHelper();

            case AnalyticsDto::PERIOD_WEEKLY:
                return new WeeklyAnalyticsIntervalHelper();

            case AnalyticsDto::PERIOD_DAILY:
                return new DailyAnalyticsIntervalHelper();

            default:
                throw new \LogicException(sprintf('Unsupported analytics type: "%s"', $type));
        }
    }
}
