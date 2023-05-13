<?php

namespace App\Message;


final class TransactionUpdateMessage implements MessageInterface
{
    private string $transaction;

    private string $transactionKey;

    private string $userEmail;

    private bool $shouldNotifyExternalApi;

    /**
     * @return string
     */
    public function getTransaction(): string
    {
        return $this->transaction;
    }

    /**
     * @param string $transaction
     */
    public function setTransaction(string $transaction): void
    {
        $this->transaction = $transaction;
    }

    /**
     * @return string
     */
    public function getTransactionKey(): string
    {
        return $this->transactionKey;
    }

    /**
     * @param string $transactionKey
     */
    public function setTransactionKey(string $transactionKey): void
    {
        $this->transactionKey = $transactionKey;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     */
    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    public function getShouldNotifyExternalApi(): bool
    {
        return $this->shouldNotifyExternalApi;
    }

    public function setShouldNotifyExternalApi(bool $shouldNotifyExternalApi): void
    {
        $this->shouldNotifyExternalApi = $shouldNotifyExternalApi;
    }
}
