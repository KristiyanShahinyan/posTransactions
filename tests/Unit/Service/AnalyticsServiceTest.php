<?php

namespace App\Tests\Unit\Service;

use App\Service\AnalyticsService;
use App\Tests\Unit\Mock\AnalyticsBalanceFilterDtoMock;
use App\Tests\Unit\Mock\ElasticResultMock;
use DateTime;
use Elastica\Client;
use Elastica\Index;
use Elastica\ResultSet;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnalyticsServiceTest extends WebTestCase
{
    private AnalyticsService $analyticsService;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()->onlyMethods(['getIndex'])->getMock();
        self::getContainer()->set(Client::class, $client);

        /** @var AnalyticsService $analyticsService */
        $analyticsService = self::getContainer()->get(AnalyticsService::class);

        $this->analyticsService = $analyticsService;
    }

    public function testGetAnalyticsData(): void
    {
        $index = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()->onlyMethods(['search'])->getMock();

        $resultSet = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()->onlyMethods(['getResponse'])->getMock();

        self::getContainer()->get(Client::class)->expects($this->exactly(2))
            ->method('getIndex')->with('transactions')->willReturn($index);

        $elasticResult = ElasticResultMock::realObject();
        $index->expects($this->exactly(2))->method('search')->willReturn($resultSet);
        $resultSet->expects($this->exactly(2))->method('getResponse')->willReturn($elasticResult);

        $result = $this->analyticsService->getAnalyticsData(uniqid(), new DateTime(), 'monthly');
        self::assertEquals($elasticResult->getData()['aggregations']['total_amount']['value'], $result['sales']);
        self::assertEquals($elasticResult->getData()['aggregations']['average_amount']['value'], $result['average']);
        self::assertEquals($elasticResult->getData()['aggregations']['total_count']['value'], $result['transactions']);
    }

    public function testGetAnalyticsStats(): void
    {
        $index = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()->onlyMethods(['search'])->getMock();

        $resultSet = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()->onlyMethods(['getResponse'])->getMock();

        self::getContainer()->get(Client::class)->expects($this->once())
            ->method('getIndex')->with('transactions')->willReturn($index);

        $elasticResult = ElasticResultMock::realObject();
        $index->expects($this->once())->method('search')->willReturn($resultSet);
        $resultSet->expects($this->once())->method('getResponse')->willReturn($elasticResult);

        $result = $this->analyticsService->getAnalyticsStats(AnalyticsBalanceFilterDtoMock::realObject(), 0);
        self::assertEquals($elasticResult->getData()['aggregations']['total_amount']['value'], $result['sales']);
        self::assertEquals($elasticResult->getData()['aggregations']['average_amount']['value'], $result['average']);
        self::assertEquals($elasticResult->getData()['aggregations']['total_count']['value'], $result['transactions']);
    }
}