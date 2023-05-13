<?php

namespace App\Tests\Unit\Util;

use App\Util\WeeklyAnalyticsIntervalHelper;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class WeeklyAnalyticsIntervalHelperTest extends TestCase
{
    private WeeklyAnalyticsIntervalHelper $helper;

    protected function setUp(): void
    {
        $this->helper = new WeeklyAnalyticsIntervalHelper();
    }

    public function testGetHistogramInterval(): void
    {
        self::assertEquals('day', $this->helper->getHistogramInterval());
    }

    public function testGetHistogramDateFormat(): void
    {
        assertEquals('E', $this->helper->getHistogramDateFormat());
    }

    public function testGetAnalyzedPeriod(): void
    {
        $result = $this->helper->getAnalyzedPeriod(new DateTime('2015-10-04 17:24:43'));
        self::assertEquals(new DateTime('2015-10-04 00:00:00'), $result->getStartDate());
        self::assertEquals(new DateTime('2015-10-10 23:59:59'), $result->getEndDate());
        self::assertEquals(new DateInterval('P1D'), $result->getDateInterval());
    }

    public function testGetStatsPeriod(): void
    {
        $result = $this->helper->getStatsPeriod(new DateTime('2015-10-04 17:24:43'));
        self::assertEquals(new DateTime('2015-09-27 17:24:43'), $result->getStartDate());
        self::assertEquals(new DateTime('2015-10-04 23:59:00'), $result->getEndDate());
        self::assertEquals(new DateInterval('P1D'), $result->getDateInterval());
    }

    public function testGetBucketIndexByDate(): void
    {
        assertEquals('Sun', $this->helper->getBucketIndexByDate(new DateTime('2015-10-04 17:24:43')));
    }
}