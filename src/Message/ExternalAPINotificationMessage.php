<?php


namespace App\Message;




class ExternalAPINotificationMessage implements MessageInterface
{
    private string $transaction;

    private string $email;

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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}