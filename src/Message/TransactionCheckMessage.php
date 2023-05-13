<?php


namespace App\Message;



class TransactionCheckMessage implements MessageInterface
{
    private string $payload;

    private array $severity;
    /**
     * @return mixed
     */
    public function getPayload(): string
    {
        return $this->payload;
    }
    /**
     * @param mixed $payload
     */
    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getSeverity(): array
    {
        return $this->severity;
    }

    /**
     * @param array $severity
     */
    public function setSeverity(array $severity): void
    {
        $this->severity = $severity;
    }


}