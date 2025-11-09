<?php

namespace App\Repository;

use App\Entity\Sanction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sanction>
 */
class SanctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sanction::class);
    }

    public function get_sanction_today(): array
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        return $this->getEntityManager()->createQuery(
            'SELECT s FROM App\Entity\Sanction s WHERE s.date_insert = :today'
        )
        ->setParameter('today', $today->format('Y-m-d'))
        ->getResult();
    }

    public function get_sanction_month(): array
    {
        $start = new \DateTime('first day of this month 00:00:00');
        $end = new \DateTime('last day of this month 23:59:59');

        return $this->getEntityManager()->createQuery(
            'SELECT s FROM App\Entity\Sanction s 
            WHERE s.date_insert BETWEEN :start AND :end'
        )
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->getResult();
    }
    //    /**
    //     * @return Sanction[] Returns an array of Sanction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sanction
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
