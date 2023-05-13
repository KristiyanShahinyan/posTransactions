<?php

namespace App\Tests\Unit\Service;

use App\Message\ExternalAPINotificationMessage;
use App\Service\NotificationService;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationServiceTest extends WebTestCase
{
    private NotificationService $notificationService;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        $messageBusInterface = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()->onlyMethods(['dispatch'])->getMock();
        self::getContainer()->set(MessageBusInterface::class, $messageBusInterface);

        /** @var NotificationService $notificationService */
        $notificationService = self::getContainer()->get(NotificationService::class);
        $this->notificationService = $notificationService;
    }

    public function testDispatchMessage(): void
    {
        self::getContainer()->get(MessageBusInterface::class)->expects($this->once())->method('dispatch')
            ->willReturn(new Envelope(new ExternalAPINotificationMessage()));

        $this->notificationService->dispatchMessage(uniqid(), 'test@test.com');
    }
}