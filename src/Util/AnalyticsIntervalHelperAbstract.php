<?php

namespace App\Util;

/**
 * Implements common logic for all controllers.
 */
abstract class AnalyticsIntervalHelperAbstract implements AnalyticsIntervalHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFormattedTimezone(\DateTime $pivotTime): string
    {
        return $pivotTime->format('P');
    }

    /**
     * {@inheritdoc}
     */
    public function getFormattedIntervalIndex(array $aggregationBucket): string
    {
        return $aggregationBucket['key_as_string'];
    }

    /**
     * Returns the beginning of the week which contains the pivot time.
     *
     * This is assumed to be monday, 00:00:00
     */
    protected function getWeekStart(\DateTime $pivotTime): \DateTime
    {
        // determine the first day of the week containing the pivot time
        $offsetDays = (int)$pivotTime->format('N') - 1;

        $weekStart = (clone $pivotTime)->sub(new \DateInterval(sprintf('P%dD', $offsetDays)));

        return new \DateTime($weekStart->format('Y-m-d 00:00:00'));
    }

    /**
     * Returns the beginning of the month which contains the pivot time.
     */
    protected function getMonthStart(\DateTime $pivotTime): \DateTime
    {
        // determine the first day of the week containing the pivot time
        $offsetDays = (int)$pivotTime->format('j') - 1;

        $monthStart = (clone $pivotTime)->sub(new \DateInterval(sprintf('P%dD', $offsetDays)));

        return new \DateTime($monthStart->format('Y-m-d 00:00:00'));
    }
}
