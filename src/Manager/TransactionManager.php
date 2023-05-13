<?php

namespace App\Manager;

use App\Constants\StatusAwareInterface;
use App\Constants\TransactionTypes;
use App\Dto\Request\IccDataDto;
use App\Dto\Request\TransactionFilterDto;
use App\Entity\Transaction;
use App\Exception\InvalidUpdateException;
use App\Repository\TransactionRepository;
use DateTime;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\TransactionRequiredException;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Elastica\Util;
use Exception;
use FOS\ElasticaBundle\Finder\FinderInterface;
use FOS\ElasticaBundle\Paginator\PaginatorAdapterInterface;
use FOS\ElasticaBundle\Paginator\TransformedPaginatorAdapter;
use Phos\Entity\EntityInterface;
use Phos\Exception\ApiException;
use Phos\Exception\ExceptionCodes;
use Phos\Helper\LoggerTrait;
use Phos\Manager\BaseManager;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * Class TransactionManager.
 * @property TransactionRepository $repository
 */
class TransactionManager extends BaseManager
{
    use LoggerTrait;
    protected EntityManager $em;

    protected FinderInterface $finder;

    /**
     * TransactionManager constructor.
     * @param EntityManagerInterface $em
     * @param FinderInterface $finder
     */
    public function __construct(EntityManagerInterface $em, FinderInterface $finder)
    {
        parent::__construct($em, Transaction::class);
        $this->finder = $finder;
    }

    /**
     * @param EntityInterface $entity
     * @param null $data
     * @throws Exception
     */
    protected function preCreate(EntityInterface $entity, $data = null): void
    {
        $entity->setTrnKey($this->generateUniqueTransactionKey());
    }

    /**
     * @param TransactionFilterDto|null $filterDto
     *
     * @return PaginatorAdapterInterface|TransformedPaginatorAdapter
     */
    public function loadTransactions(?TransactionFilterDto $filterDto = null)
    {
        $query = new BoolQuery();

        if ($filterDto !== null) {

            $this->elasticFilter($filterDto->getMerchant(), 'merchant', $query);
            $this->elasticFilter($filterDto->getTrnType(), 'transaction_type', $query);
            $this->elasticFilter($filterDto->getRemoteServiceTransaction(), 'remote_service_transaction', $query);
            $this->elasticFilter($filterDto->getStatus(), 'status', $query);
            $this->elasticFilter($filterDto->getUser(), 'user_token', $query);
            $this->elasticFilter($filterDto->getDeviceId(), 'device_id', $query);
            $this->elasticFilter($filterDto->getTid(), 'terminal_id', $query);
            $this->elasticFilter($filterDto->getMid(), 'merchant_id', $query);
            $this->elasticFilter($filterDto->getProcessor(), 'processor', $query);
            $this->elasticFilter($filterDto->getChannel(), 'channel', $query);
            $this->elasticFilter($filterDto->getCardType(), 'card_type', $query);
            $this->elasticFilter($filterDto->getAcquirerCode(), 'acquirer_code', $query);
            $this->elasticFilter($filterDto->getCurrency(), 'currency', $query);
            $this->elasticFilter($filterDto->getRetrievalReferenceNumber(), 'retrieval_reference_number', $query);
            $this->elasticFilter($filterDto->getTrnKey(), 'trn_key', $query);

            if ($filterDto->getThreeDs() === true)
                $query->addMust(new MatchQuery('sca_type', Transaction::SCA_TYPE_3DS_PROTECTED));

            if ($filterDto->getThreeDs() === false)
                $query->addMustNot(new MatchQuery('sca_type', Transaction::SCA_TYPE_3DS_PROTECTED));

            if ($filterDto->getStartDate() !== null && $filterDto->getEndDate() !== null && !$filterDto->getExactDate()) {
                $query->addMust(new Query\Range(
                    'pos_local_datetime',
                    array(
                        'gte' => Util::convertDate($filterDto->getStartDate()->setTime(0, 0)->format('Y-m-d H:i:s')),
                        'lte' => Util::convertDate($filterDto->getEndDate()->setTime(23, 59, 59)->format('Y-m-d H:i:s')),
                    )
                ));
            } else if ($filterDto->getStartDate() !== null && $filterDto->getEndDate() !== null && $filterDto->getExactDate()) {
                $query->addMust(new Query\Range(
                    'pos_local_datetime',
                    array(
                        'gte' => Util::convertDate($filterDto->getStartDate()->format('Y-m-d H:i:s')),
                        'lte' => Util::convertDate($filterDto->getEndDate()->format('Y-m-d H:i:s')),
                    )
                ));
            }

            if ($filterDto->getTrnTypes() !== null) {
                $query->addMust(new Query\Terms(
                    'transaction_type',
                    $filterDto->getTrnTypes()
                ));
            }

            if ($filterDto->getCardTypes() !== null) {
                $query->addMust(new Query\Terms(
                    'card_type',
                    $filterDto->getCardTypes()
                ));
            }

            if ($filterDto->getStatuses() !== null) {
                $query->addMust(new Query\Terms(
                    'status',
                    $filterDto->getStatuses()
                ));
            }
        }

        $sortQuery = new Query($query);

        if ($filterDto->getSort()){
            foreach($filterDto->getSort() as $sortField => $order){
                $sortQuery->setSort([$sortField => ['order' => $order]]);
            }
        } else {
            $sortQuery->setSort(array('pos_local_datetime' => array('order' => 'desc')));
        }

        return $this->finder->createPaginatorAdapter($sortQuery);
    }

    /**
     * @param $field
     * @param string $key
     * @param BoolQuery $query
     */
    private function elasticFilter($field, string $key, BoolQuery $query)
    {
        if ($field !== null && trim($field) !== '') {
            $match = new MatchQuery();
            $match->setField($key, $field);
            $query->addMust($match);
        }
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    private function generateUniqueTransactionKey(): string
    {
        do {
            $key = bin2hex(random_bytes(16));
        } while (!$this->checkUniqueTransactionKey($key));

        return $key;
    }

    /**
     * @param $trnKey
     * @return bool
     * @throws ApiException
     */
    private function checkUniqueTransactionKey($trnKey): bool
    {
        if (!$trnKey) {
            return false;
        }

        return $this->repository->findOneBy(['trnKey' => $trnKey], [], false) === null;
    }

    /**
     * @param $refundableAmount
     * @param string $trnKey
     * @return Transaction
     * @throws ApiException
     * @throws NonUniqueResultException
     * @throws TransactionRequiredException
     * @throws \Doctrine\DBAL\Exception
     */
    public function secureUpdateRefundable($refundableAmount, string $trnKey): Transaction
    {
        $transaction = $this->lockTransaction($trnKey);
        $this->setRefundableAmount($refundableAmount, $transaction);

        return $this->flushTransaction($transaction);
    }

    /**
     * @param string $trnKey
     * @return Transaction
     * @throws NonUniqueResultException
     * @throws TransactionRequiredException
     * @throws \Doctrine\DBAL\Exception
     */
    public function lockTransaction(string $trnKey): Transaction
    {
        $this->_em->getConnection()->beginTransaction();

        $qb = $this->repository->createQueryBuilder('t');
        $qb->andWhere('t.trnKey = :key')
            ->setParameter('key', $trnKey);
        $qb->setMaxResults(1);
        $query = $qb->getQuery();
        $query->setLockMode(LockMode::PESSIMISTIC_WRITE);

        /** @var Transaction $transaction */
        $transaction = $query->getOneOrNullResult();

        return $transaction;
    }

    /**
     * @param $refundableAmount
     * @param $transaction
     * @return void
     * @throws ApiException
     * @throws \Doctrine\DBAL\Exception
     */
    public function setRefundableAmount($refundableAmount, $transaction): void
    {
        $amount = $transaction->getRefundableAmount() + $refundableAmount;
        if (round($amount, 4) > round($transaction->getAmount(), 4) || $amount < 0) {
            $this->_em->getConnection()->rollBack();
            throw new ApiException(ExceptionCodes::VALIDATION_ERROR);
        }

        $transaction->setRefundableAmount(number_format($amount, 4, '.', ''));

        // No void for open banking transactions
        if ($transaction->getTransactionType() !== 18) {
            $transaction->setVoidable(round($amount, 4) == round($transaction->getAmount(), 4));
        }
    }

    /**
     * @param $transaction
     * @return Transaction
     * @throws \Doctrine\DBAL\Exception
     */
    public function flushTransaction($transaction): Transaction
    {
        $this->_em->persist($transaction);
        $this->_em->flush();
        $this->_em->getConnection()->commit();

        return $transaction;
    }

    /**
     * @param Transaction $transaction
     * @param string $data
     * @return void
     * @throws ApiException
     * @throws InvalidUpdateException
     */
    public function updateData(Transaction $transaction, string $data): EntityInterface
    {
        $originalStatus = $transaction->getStatus();
        $originalScaType = $transaction->getScaType();
        $originalRefundableAmount = $transaction->getRefundableAmount();
        $originalVoidable = $transaction->getVoidable();

        /**
         * @var $entity Transaction
         */
        $entity = $this->deserialize($data, $this->entity, ['groups' => 'update', AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true], $transaction);

        $iccData = $this->createIccData($data);
        if (!empty($iccData))
            $entity->setIccData($iccData);
        $this->validate($entity, 'update');

        $this->checkStatusUpdate($originalStatus, $entity->getStatus());
        $this->checkScaTypeUpdate($originalScaType, $entity->getScaType());
        $this->checkRefundableAmount($originalRefundableAmount, $entity);
        $this->checkVoidableAmount($originalVoidable, $entity);

        $this->preUpdate($entity);
        $this->preFlush($entity);

        return $entity;
    }

    /**
     * @throws Exception
     */
    public function updateTransactionsVoidable(string $acquirer_code, ?string $affiliate, ?string $instance, ?string $date): int
    {
        $createDate = is_null($date) ? new DateTime() : new DateTime($date);
        $filter = [
            'pos_acquiring_institution_code' => $acquirer_code,
            'create_date' => $createDate->format('Y-m-d H:00:00')
        ];
        if (!is_null($affiliate)) {
            $filter['affiliate'] = $affiliate;
        }
        if (!is_null($instance)) {
            $filter['instance'] = $instance;
        }

        return $this->repository->updateTransactionsVoidable($filter);
    }

    /**
     * @param int $oldStatus
     * @param int $newStatus
     * @return void
     * @throws InvalidUpdateException
     */
    private function checkStatusUpdate(int $oldStatus, int $newStatus): void
    {
        switch ($oldStatus) {
            case StatusAwareInterface::FAILED:
            case StatusAwareInterface::SUCCESSFUL:
                if ($oldStatus != $newStatus)
                    throw new InvalidUpdateException('Cannot change transaction status from ' . $oldStatus . ' to new status ' . $newStatus);
        }
    }

    /**
     * @param int $oldScaType
     * @param int $newScaType
     * @return void
     * @throws InvalidUpdateException
     */
    private function checkScaTypeUpdate(int $oldScaType, int $newScaType): void
    {
        if (empty($oldScaType))
            return;

        if ($oldScaType == Transaction::SCA_TYPE_PIN_PROTECTED && $newScaType == Transaction::SCA_TYPE_REGULAR)
            throw new InvalidUpdateException('Cannot change sca type from ' . $oldScaType . ' to ' . $newScaType);
    }

    private function createIccData(string $data): ?array
    {
        /** @var IccDataDto $iccData */
        $iccData = $this->deserialize($data, IccDataDto::class, [AbstractObjectNormalizer::SKIP_NULL_VALUES]);

        return $iccData->asArray();
    }

    private function checkRefundableAmount($oldRefundableAmount, Transaction $entity): void
    {
        if ($entity->getRefundableAmount() !== $oldRefundableAmount && $entity->getTransactionType() != TransactionTypes::AUTH) {
            $entity->setRefundableAmount($oldRefundableAmount);
        }
    }

    private function checkVoidableAmount($oldVoidable, Transaction $entity): void
    {
        if ($entity->getVoidable() !== $oldVoidable && $entity->getTransactionType() != TransactionTypes::AUTH) {
            $entity->setVoidable($oldVoidable);
        }
    }
}
