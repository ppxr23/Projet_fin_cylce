<?php

namespace App\Repository;

use App\Entity\Absence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Absence>
 */
class AbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absence::class);
    }

    public function get_absence_today(): array
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        return $this->getEntityManager()->createQuery(
            'SELECT a FROM App\Entity\Absence a WHERE a.date = :today'
        )
        ->setParameter('today', $today->format('Y-m-d'))
        ->getResult();
    }

    public function get_absence_month(): array
    {
        $start = new \DateTime('first day of this month 00:00:00');
        $end = new \DateTime('last day of this month 23:59:59');

        return $this->getEntityManager()->createQuery(
            'SELECT a FROM App\Entity\Absence a 
            WHERE a.date BETWEEN :start AND :end'
        )
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->getResult();
    }

    //    /**
    //     * @return Absence[] Returns an array of Absence objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Absence
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
