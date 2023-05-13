<?php

namespace App\Service;

use App\Dto\Response\TransactionDto;
use App\Entity\Transaction;
use App\Message\ExternalAPINotificationMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationService
{
    private MessageBusInterface $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatchMessage(string $transaction, string $email)
    {
        $this->messageBus->dispatch($this->buildMessage($transaction, $email));
    }

    private function buildMessage(string $transaction, string $email)
    {
        $message = new ExternalAPINotificationMessage();
        $message->setTransaction($transaction);
        $message->setEmail($email);

        return $message;
    }
}