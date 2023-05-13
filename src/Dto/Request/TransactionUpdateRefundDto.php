<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;

class TransactionUpdateRefundDto implements DtoInterface
{
    private string $refundable_amount;

    /**
     * @return string
     */
    public function getRefundableAmount(): string
    {
        return $this->refundable_amount;
    }

    /**
     * @param string $refundable_amount
     */
    public function setRefundableAmount(string $refundable_amount): void
    {
        $this->refundable_amount = $refundable_amount;
    }
}