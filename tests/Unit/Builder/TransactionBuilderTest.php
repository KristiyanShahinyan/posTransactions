<?php

namespace App\Tests\Unit\Builder;

use App\Builder\TransactionBuilder;
use App\Tests\Unit\Mock\TransactionMock;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class TransactionBuilderTest extends TestCase
{
    private TransactionBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new TransactionBuilder();
    }

    public function testBuildTransaction(): void
    {
        $transaction = TransactionMock::realObject();
        $result = $this->builder->buildTransaction($transaction);
        assertEquals($transaction->getPosSystemTraceAuditNumber(), $result->getStan());
        assertEquals($transaction->getAmount(), $result->getAmount());
        assertEquals($transaction->getCardToken(), $result->getCard());
    }

    public function testBuildTransactionList(): void
    {
        $firstTransaction = TransactionMock::realObject();
        $secondTransaction = TransactionMock::realObject();

        $result = $this->builder->buildTransactionList([$firstTransaction, $secondTransaction]);
        self::assertIsArray($result->getItems());
    }
}