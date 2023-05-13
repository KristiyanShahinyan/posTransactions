<?php

namespace App\Repository;

use App\Entity\Reconciliation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reconciliation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reconciliation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reconciliation[]    findAll()
 * @method Reconciliation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReconciliationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reconciliation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reconciliation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Reconciliation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string $affiliate
     * @return Reconciliation|null Returns an array of Reconciliation objects
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByAffiliate(string $affiliate): ?Reconciliation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.affiliate LIKE :affiliate')
            ->setParameter('affiliate', '%'.$affiliate.'%')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Reconciliation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
