<?php

namespace App\Tests\Unit\Command;

use App\Manager\TransactionManager;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CheckVoidableTransactionsCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        $transactionManager = $this->getMockBuilder(TransactionManager::class)
            ->disableOriginalConstructor()->onlyMethods(['updateTransactionsVoidable'])->getMock();
        self::getContainer()->set(TransactionManager::class, $transactionManager);

        $application = new Application(self::$kernel);
        $command = $application->find('app:check-voidable-transactions');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecute(): void
    {
        self::getContainer()->get(TransactionManager::class)->expects($this->once())
            ->method('updateTransactionsVoidable')->with('234234', 'Affiliate', 'Instance', '2022-11-16 14:04:44')->willReturn(5);

        $this->commandTester->execute([
            '--acquirer_code' => '234234',
            '--affiliate' => 'Affiliate',
            '--instance' => 'Instance',
            '--date' => '2022-11-16 14:04:44'
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        self::assertStringContainsString('5 transactions updated', $this->commandTester->getDisplay());
    }
}