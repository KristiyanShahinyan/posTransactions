<?php

namespace App\Tests\Unit\Util;

use App\Util\DailyAnalyticsIntervalHelper;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;

class DailyAnalyticsIntervalHelperTest extends TestCase
{
    private DailyAnalyticsIntervalHelper $helper;

    protected function setUp(): void
    {
        $this->helper = new DailyAnalyticsIntervalHelper();
    }

    public function testGetHistogramInterval(): void
    {
        self::assertEquals('hour', $this->helper->getHistogramInterval());
    }

    public function testGetHistogramDateFormat(): void
    {
        self::assertEquals('HH', $this->helper->getHistogramDateFormat());
    }

    public function testGetBucketIndexByDate(): void
    {
        self::assertEquals(17, $this->helper->getBucketIndexByDate(new DateTime('2015-10-04 17:24:43')));
    }

    public function testGetAnalyzedPeriod(): void
    {
        $result = $this->helper->getAnalyzedPeriod(new DateTime('2015-10-04 17:24:43'));
        self::assertEquals(new DateTime('2015-10-04 00:00:00'), $result->getStartDate());
        self::assertEquals(new DateTime('2015-10-04 23:59:59'), $result->getEndDate());
        self::assertEquals(new DateInterval('PT1H'), $result->getDateInterval());
    }

    public function testGetStatsPeriod(): void
    {
        $result = $this->helper->getStatsPeriod(new DateTime('2015-10-04 17:24:43'));
        self::assertEquals(new DateTime('2015-10-03 17:24:43'), $result->getStartDate());
        self::assertEquals(new DateTime('2015-10-04 17:24:43'), $result->getEndDate());
        self::assertEquals(new DateInterval('PT1H'), $result->getDateInterval());
    }
}