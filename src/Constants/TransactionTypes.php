<?php

namespace App\Constants;

abstract class TransactionTypes
{
    const DEPOSIT      = 1;
    const WITHDRAW     = 2;
    const SEND         = 3;
    const REQUEST      = 4;
    const PAYMENT      = 5;
    const GPS          = 6;
    const BANK_DEPOSIT = 7;
    const RECEIVE      = 8;
    const TRANSFER     = 9;
    const POS          = 10;
    const ATM          = 11;
    /**
     * Authorisation on POS terminal.
     */
    const AUTH       = 12;
    const ORDER_CARD = 13;
    const REVERT     = 14;
    const FEE        = 15;
    /**
     * Refund for a specific amount (full or partial).
     */
    const REFUND = 16;
    /**
     * Reversal of a purchase or a refund.
     */
    const VOID = 17;
}
