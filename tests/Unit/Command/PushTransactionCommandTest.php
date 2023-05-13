<?php

namespace App\Tests\Unit\Command;

use App\Repository\TransactionRepository;
use App\Service\NotificationService;
use App\Tests\Unit\Mock\TransactionMock;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class PushTransactionCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        $notificationService = $this->getMockBuilder(NotificationService::class)
            ->disableOriginalConstructor()->onlyMethods(['dispatchMessage'])->getMock();
        self::getContainer()->set(NotificationService::class, $notificationService);

        $transactionRepository = $this->getMockBuilder(TransactionRepository::class)
            ->disableOriginalConstructor()->onlyMethods(['find'])->getMock();
        self::getContainer()->set(TransactionRepository::class, $transactionRepository);

        $application = new Application(self::$kernel);
        $command = $application->find('app:push-transaction');
        $this->commandTester = new CommandTester($command);
    }

    public function testNoTransaction(): void
    {
        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful();
        self::assertStringNotContainsString('Processed transaction with id', $this->commandTester->getDisplay());
    }

    public function testInvalidTransaction(): void
    {
        self::getContainer()->get(TransactionRepository::class)->expects($this->once())
            ->method('find')->with('1231232')->willReturn(null);

        $this->commandTester->execute(['--id' => '1231232']);

        $this->commandTester->assertCommandIsSuccessful();
        self::assertStringNotContainsString('Processed transaction with id', $this->commandTester->getDisplay());
    }

    public function testTransaction(): void
    {
        self::getContainer()->get(TransactionRepository::class)->expects($this->once())
            ->method('find')->with('1231232')->willReturn(TransactionMock::realObject());

        $this->commandTester->execute(['--id' => '1231232']);

        $this->commandTester->assertCommandIsSuccessful();
        self::assertStringContainsString('Processed transaction with id: 1231232', $this->commandTester->getDisplay());
    }
}