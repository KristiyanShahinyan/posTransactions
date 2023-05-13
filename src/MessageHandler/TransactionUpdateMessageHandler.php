<?php

namespace App\MessageHandler;

use App\Builder\TransactionBuilder;
use App\Constants\StatusAwareInterface;
use App\Constants\TransactionTypes;
use App\Entity\Transaction;
use App\Exception\InvalidUpdateException;
use App\Manager\TransactionManager;
use App\Message\TransactionUpdateMessage;
use App\Service\MonitoringService;
use App\Service\NotificationService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\TransactionRequiredException;
use Phos\Exception\ApiException;
use Phos\Helper\LoggerTrait;
use Phos\Helper\SerializationTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class TransactionUpdateMessageHandler implements MessageHandlerInterface
{
    use SerializationTrait, LoggerTrait;
    /**
     * @var TransactionManager
     */
    private TransactionManager $transactionManager;
    /**
     * @var MonitoringService
     */
    private MonitoringService $monitoringService;
    /**
     * @var NotificationService
     */
    private NotificationService $notificationService;
    /**
     * @var TransactionBuilder
     */
    private TransactionBuilder $builder;

    public function __construct
    (
        TransactionManager $transactionManager,
        MonitoringService $monitoringService,
        NotificationService $notificationService,
        TransactionBuilder $builder
    )
    {
        $this->transactionManager = $transactionManager;
        $this->monitoringService = $monitoringService;
        $this->notificationService = $notificationService;
        $this->builder = $builder;
    }

    /**
     * @param TransactionUpdateMessage $message
     * @return void
     * @throws ApiException
     * @throws NonUniqueResultException
     * @throws TransactionRequiredException
     * @throws Exception
     */
    public function __invoke(TransactionUpdateMessage $message)
    {
        $transaction = $this->transactionManager->lockTransaction($message->getTransactionKey());

        try {
            /** @var Transaction $transaction */
            $transaction = $this->transactionManager->updateData($transaction, $message->getTransaction());
        } catch (InvalidUpdateException $e) {
            $this->transactionManager->__get('em')->rollback();
            $this->logger->error($e->getMessage());
            return; // throwing this exception means that it's an outdated update, so it should be ignored.
        }

        $this->transactionManager->flushTransaction($transaction);

        if ($this->isFailedRefund($transaction))
            $this->transactionManager->secureUpdateRefundable($transaction->getAmount(), $transaction->getLinkedTransaction()->getTrnKey());

        if ($this->isFailedVoid($transaction))
            $this->transactionManager->secureUpdateRefundable($transaction->getLinkedTransaction()->getAmount(), $transaction->getLinkedTransaction()->getTrnKey());

        $this->monitoringService->lowSeverityMonitoring($this->serialize($transaction));

        if ($message->getShouldNotifyExternalApi()) {
            $this->notificationService->dispatchMessage($this->serialize($transaction), $message->getUserEmail());
        }
    }

    private function isFailedRefund(Transaction $transaction): bool
    {
        return $transaction->getStatus() == StatusAwareInterface::FAILED && $transaction->getTransactionType() == TransactionTypes::REFUND;
    }

    private function isFailedVoid(Transaction $transaction): bool
    {
        return $transaction->getStatus() == StatusAwareInterface::FAILED && $transaction->getTransactionType() == TransactionTypes::VOID;
    }
}
