<?php


namespace App\Service;


use App\Message\TransactionCheckMessage;
use Symfony\Component\Messenger\MessageBusInterface;


class MonitoringService
{
    private const HIGH_SEVERITY = 3;
    private const MID_SEVERITY = 2;
    private const LOW_SEVERITY = 1;

    private MessageBusInterface $messageBus;

    /**
     * MonitoringService constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function lowSeverityMonitoring(string $trnDto)
    {
        $this->messageBus->dispatch($this->buildMessage($trnDto));
    }

    private function buildMessage(string $dto): TransactionCheckMessage
    {
        $severity = [self::LOW_SEVERITY, self::MID_SEVERITY];
        $message = new TransactionCheckMessage();
        $message->setPayload($dto);
        $message->setSeverity($severity);

        return $message;
    }

}