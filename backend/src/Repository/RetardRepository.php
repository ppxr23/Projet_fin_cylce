<?php

namespace App\Repository;

use App\Entity\Retard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Retard>
 */
class RetardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Retard::class);
    }

    public function get_retard_today(): array
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        return $this->getEntityManager()->createQuery(
            'SELECT r FROM App\Entity\Retard r WHERE r.date = :today'
        )
        ->setParameter('today', $today->format('Y-m-d'))
        ->getResult();
    }

    public function get_retard_month(): array
    {
        $start = new \DateTime('first day of this month 00:00:00');
        $end = new \DateTime('last day of this month 23:59:59');

        return $this->getEntityManager()->createQuery(
            'SELECT r FROM App\Entity\Retard r 
            WHERE r.date BETWEEN :start AND :end'
        )
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->getResult();
    }

    //    /**
    //     * @return Retard[] Returns an array of Retard objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Retard
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
