<?php

namespace App\Tests\Unit\Service;

use App\Message\TransactionCheckMessage;
use App\Service\MonitoringService;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class MonitoringServiceTest extends WebTestCase
{
    private MonitoringService $monitoringService;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        $messageBusInterface = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()->onlyMethods(['dispatch'])->getMock();
        self::getContainer()->set(MessageBusInterface::class, $messageBusInterface);

        /** @var MonitoringService $monitoringService */
        $monitoringService = self::getContainer()->get(MonitoringService::class);
        $this->monitoringService = $monitoringService;
    }

    public function testLowSeverityMonitoring(): void
    {
        self::getContainer()->get(MessageBusInterface::class)->expects($this->once())->method('dispatch')
            ->willReturn(new Envelope(new TransactionCheckMessage()));

        $this->monitoringService->lowSeverityMonitoring(uniqid());
    }
}