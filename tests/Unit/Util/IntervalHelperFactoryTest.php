<?php

namespace App\Tests\Unit\Util;

use App\Util\DailyAnalyticsIntervalHelper;
use App\Util\IntervalHelperFactory;
use App\Util\MonthlyAnalyticsIntervalHelper;
use App\Util\WeeklyAnalyticsIntervalHelper;
use LogicException;
use PHPUnit\Framework\TestCase;

class IntervalHelperFactoryTest extends TestCase
{
    private IntervalHelperFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new IntervalHelperFactory();
    }

    public function testCreate(): void
    {
        self::assertEquals(MonthlyAnalyticsIntervalHelper::class, get_class($this->factory->create('monthly')));
        self::assertEquals(WeeklyAnalyticsIntervalHelper::class, get_class($this->factory->create('weekly')));
        self::assertEquals(DailyAnalyticsIntervalHelper::class, get_class($this->factory->create('daily')));

        $this->expectException(LogicException::class);
        $this->factory->create('error');
    }
}