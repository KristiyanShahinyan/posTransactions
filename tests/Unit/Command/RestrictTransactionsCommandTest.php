<?php

namespace App\Tests\Unit\Command;

use App\Repository\TransactionRepository;
use App\Tests\Unit\Mock\TransactionMock;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RestrictTransactionsCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        $transactionRepository = $this->getMockBuilder(TransactionRepository::class)
            ->disableOriginalConstructor()->onlyMethods(['getRestrictedTransactions'])->getMock();
        self::getContainer()->set(TransactionRepository::class, $transactionRepository);

        $application = new Application(self::$kernel);
        $command = $application->find('app:restrict-transactions');
        $this->commandTester = new CommandTester($command);
    }

    public function testRejectTransactions(): void
    {
        self::getContainer()->get(TransactionRepository::class)->expects($this->once())->method('getRestrictedTransactions')
            ->with(['transactionType' => 12, 'period' => 100])->willReturn([TransactionMock::realObject()]);

        $this->commandTester->execute([
            'period' => 100
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        self::assertStringContainsString('Transactions were updated successfully.', $this->commandTester->getDisplay());
    }
}