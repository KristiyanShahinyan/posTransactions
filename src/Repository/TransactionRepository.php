<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\ORM\AbstractQuery;
use Exception;
use Phos\Repository\BaseRepository;
use DateTime;

class TransactionRepository extends BaseRepository
{
    /**
     * @param array $filter
     *
     * @return iterable|array
     * @throws Exception
     */
    public function getRestrictedTransactions(array $filter)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.transactionType = :transactionType')
            ->andWhere('t.createDate <= :periodDate')
            ->andWhere('t.voidable = true OR t.refundableAmount != 0')
            ->setParameters([
                'transactionType' => $filter['transactionType'],
                'periodDate' => new DateTime(sprintf('-%s days', $filter['period']))
            ])
            ->getQuery()
            ->toIterable();
    }

    /**
     * @param array $filter
     * @return Transaction[]|array
     */
    public function getTransactionsForReconciliation(array $filter): array
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.status = 1')
            ->andWhere('t.createDate >= :startDate')
            ->andWhere('t.createDate <= :endDate')
            ->andWhere('t.reconciliationBatch IS NULL')
            ->setParameters([
                'startDate' => $filter['startDate'],
                'endDate' => $filter['endDate']
            ])
            ->orderBy('t.id', 'ASC');
        if (array_key_exists('affiliate', $filter)) {
            $qb->andWhere('t.affiliate IN (:affiliate)')
                ->setParameter('affiliate', explode(',', $filter['affiliate']));
        } elseif (array_key_exists('acquirer', $filter)) {
            $qb->andWhere('t.posAcquiringInstitutionCode = :acquirer')
                ->setParameter('acquirer', $filter['acquirer']);
        }
        $qb->leftJoin('t.linkedTransaction', 'linkedTransaction')->addSelect('linkedTransaction');

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    public function markTransactionsAsReconciled(array $trnIds, int $batch)
    {
        return $this->createQueryBuilder('t')
            ->update()
            ->set('t.reconciliationBatch', $batch)
            ->set('t.voidable', 'false')
            ->where('t.id IN (:ids)')
            ->setParameter('ids', $trnIds)
            ->getQuery()
            ->getResult();
    }

    public function updateTransactionsVoidable(array $filter): int
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.posAcquiringInstitutionCode = :posAcquiringInstitutionCode')
            ->andWhere('t.voidable = true')
            ->andWhere('t.reconciliationBatch is null')
            ->andWhere('t.createDate < :createDate')
            ->setParameters([
                'posAcquiringInstitutionCode' => $filter['pos_acquiring_institution_code'],
                'createDate' => $filter['create_date']
            ]);

        if (isset($filter['affiliate'])) {
            $qb->andWhere('t.affiliate = :affiliate')
                ->setParameter('affiliate', $filter['affiliate']);
        }
        if (isset($filter['instance'])) {
            $qb->andWhere('t.instance = :instance')
                ->setParameter('instance', $filter['instance']);
        }

        return $qb->update()->set('t.voidable', 'false')->getQuery()->execute();
    }
}
