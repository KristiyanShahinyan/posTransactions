<?php

namespace App\Tests\Unit\Manager;

use App\Constants\StatusAwareInterface;
use App\Constants\TransactionTypes;
use App\Entity\Transaction;
use App\Exception\InvalidUpdateException;
use App\Manager\TransactionManager;
use App\Tests\Unit\Mock\TransactionMock;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionManagerTest extends WebTestCase
{
    private TransactionManager $transactionManager;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }

        /** @var TransactionManager $transactionManager */
        $transactionManager = self::getContainer()->get(TransactionManager::class);

        $this->transactionManager = $transactionManager;
    }

    public function testUpdateData(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setAmount(50);
        $data = '{"amount":5}';
        self::assertEquals(5, $this->transactionManager->updateData($transaction, $data)->getAmount());
    }

    public function testUpdateDataStatus(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setStatus(StatusAwareInterface::SUCCESSFUL);
        $data = '{"status":-1}';

        $this->expectException(InvalidUpdateException::class);
        $this->transactionManager->updateData($transaction, $data);
    }

    public function testUpdateDataScaType(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setScaType(Transaction::SCA_TYPE_PIN_PROTECTED);
        $data = '{"sca_type":1}';

        $this->expectException(InvalidUpdateException::class);
        $this->transactionManager->updateData($transaction, $data);
    }

    public function testUpdateDataRefundableAmountRefund(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setTransactionType(TransactionTypes::REFUND);
        $transaction->setRefundableAmount(null);
        $data = '{"refundable_amount":10}';

        self::assertNull($this->transactionManager->updateData($transaction, $data)->getRefundableAmount());
    }

    public function testUpdateDataRefundableAmountVoid(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setTransactionType(TransactionTypes::VOID);
        $transaction->setRefundableAmount(null);
        $data = '{"refundable_amount":10}';

        self::assertNull($this->transactionManager->updateData($transaction, $data)->getRefundableAmount());
    }

    public function testUpdateDataRefundableAmountSale(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setTransactionType(TransactionTypes::AUTH);
        $transaction->setRefundableAmount(0);
        $data = '{"refundable_amount":10}';

        self::assertEquals(10, $this->transactionManager->updateData($transaction, $data)->getRefundableAmount());
    }

    public function testUpdateDataVoidableRefund(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setTransactionType(TransactionTypes::REFUND);
        $transaction->setVoidable(null);
        $data = '{"voidable":true}';

        self::assertNull($this->transactionManager->updateData($transaction, $data)->getVoidable());
    }

    public function testUpdateDataVoidableVoid(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setTransactionType(TransactionTypes::VOID);
        $transaction->setVoidable(null);
        $data = '{"voidable":true}';

        self::assertNull($this->transactionManager->updateData($transaction, $data)->getVoidable());
    }

    public function testUpdateDataVoidableSale(): void
    {
        $transaction = TransactionMock::realObject();
        $transaction->setTransactionType(TransactionTypes::AUTH);
        $transaction->setVoidable(null);
        $data = '{"voidable":true}';

        self::assertTrue($this->transactionManager->updateData($transaction, $data)->getVoidable());
    }
}