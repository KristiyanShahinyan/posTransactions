<?php

namespace App\Tests\Unit\Command;

use App\Message\ReconciliationMessage;
use App\Repository\ReconciliationRepository;
use App\Repository\TransactionRepository;
use App\Tests\Unit\Mock\ReconciliationMock;
use App\Tests\Unit\Mock\TransactionMock;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ReconciliationCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        $messageBusInterface = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()->onlyMethods(['dispatch'])->getMock();
        self::getContainer()->set(MessageBusInterface::class, $messageBusInterface);

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()->onlyMethods(['__call'])->getMock();
        self::getContainer()->set(S3Client::class, $s3Client);

        $reconciliationRepository = $this->getMockBuilder(ReconciliationRepository::class)
            ->disableOriginalConstructor()->onlyMethods(['findOneBy', 'findByAffiliate'])->getMock();
        self::getContainer()->set(ReconciliationRepository::class, $reconciliationRepository);

        $transactionRepository = $this->getMockBuilder(TransactionRepository::class)
            ->disableOriginalConstructor()->onlyMethods(['getTransactionsForReconciliation', 'markTransactionsAsReconciled'])->getMock();
        self::getContainer()->set(TransactionRepository::class, $transactionRepository);

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()->onlyMethods(['persist', 'flush', 'clear'])->getMock();
        self::getContainer()->set('doctrine.orm.default_entity_manager', $entityManager);

        $application = new Application(self::$kernel);
        $command = $application->find('app:reconciliation');
        $this->commandTester = new CommandTester($command);
    }

    public function testReconciliation(): void
    {
        $from = '2022-12-12 12:12:12';
        $to = '2022-12-13 12:12:12';
        $acquirer = 'Test Acquirer';
        $affiliate = 'Test Affiliate';

        $reconciliation = ReconciliationMock::realObject();

        self::getContainer()->get(ReconciliationRepository::class)->expects($this->once())
            ->method('findOneBy')->with(['acquirer' => $acquirer])->willReturn($reconciliation);

        self::getContainer()->get(TransactionRepository::class)->expects($this->once())->method('getTransactionsForReconciliation')
            ->with(['startDate' =>$from, 'endDate' => $to, 'acquirer' => $acquirer])->willReturn([TransactionMock::asArray()]);

        self::getContainer()->get('doctrine.orm.default_entity_manager')->expects($this->once())->method('persist')
            ->with($reconciliation);

        self::getContainer()->get('doctrine.orm.default_entity_manager')->expects($this->once())->method('flush');

        self::getContainer()->get(MessageBusInterface::class)->expects($this->once())->method('dispatch')
            ->willReturn(new Envelope(new ReconciliationMessage()));

        $this->commandTester->execute([
            '--from' => $from,
            '--to' => $to,
            '--acquirer' => $acquirer,
            '--affiliate' => $affiliate
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        self::assertStringContainsString('1 transactions has been successfully processed', $this->commandTester->getDisplay());
    }

    public function testReconciliationWithAffiliate(): void
    {
        $from = '2022-12-12 12:12:12';
        $to = '2022-12-13 12:12:12';

        $reconciliation = ReconciliationMock::realObject();

        self::getContainer()->get(ReconciliationRepository::class)->expects($this->once())
            ->method('findByAffiliate')->with($reconciliation->getAffiliate())->willReturn($reconciliation);

        self::getContainer()->get(TransactionRepository::class)->expects($this->once())->method('getTransactionsForReconciliation')
            ->with(['startDate' =>$from, 'endDate' => $to, 'affiliate' => $reconciliation->getAffiliate()])->willReturn([TransactionMock::asArray()]);

        self::getContainer()->get('doctrine.orm.default_entity_manager')->expects($this->once())->method('persist')
            ->with($reconciliation);

        self::getContainer()->get('doctrine.orm.default_entity_manager')->expects($this->once())->method('flush');

        self::getContainer()->get(MessageBusInterface::class)->expects($this->once())->method('dispatch')
            ->willReturn(new Envelope(new ReconciliationMessage()));

        $this->commandTester->execute([
            '--from' => $from,
            '--to' => $to,
            '--affiliate' => $reconciliation->getAffiliate()
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        self::assertStringContainsString('1 transactions has been successfully processed', $this->commandTester->getDisplay());
    }

    public function testReconciliationNotFound(): void
    {
        self::getContainer()->get(ReconciliationRepository::class)->expects($this->once())
            ->method('findOneBy')->with(['acquirer' => 'acquirer'])->willReturn(null);

        $this->commandTester->execute([
            '--acquirer' => 'acquirer'
        ]);

        self::assertEquals(1, $this->commandTester->getStatusCode());
        self::assertStringContainsString('No configuration for the given acquirer/affiliate.', $this->commandTester->getDisplay());
    }
}