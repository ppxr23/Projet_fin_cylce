<?php

namespace App\Repository;

use App\Entity\Sanction;
use App\Entity\Vigie;
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

    public function getVigieByMatricule(string $matricule): ?Vigie
    {
        $result = $this->createQueryBuilder('u')
            ->join('u.vigie', 'v')
            ->addSelect('v')
            ->where('u.matricule = :matricule')
            ->setParameter('matricule', $matricule)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result->getVigie() : null;
    }

    public function get_sanction_today($matricule = null, $roles = null, $all = false): array
    {
        if (!$all) {
            $today = new \DateTime();
            $today->setTime(0, 0, 0);

            if ($roles == 'RH') {
                return $this->getEntityManager()->createQuery(
                    'SELECT s FROM App\Entity\Sanction s WHERE s.date_insert = :today'
                )
                ->setParameter('today', $today->format('Y-m-d'))
                ->getResult();
            } else {
                $vigie = $matricule ? $this->getVigieByMatricule($matricule) : null;

                return $this->getEntityManager()->createQuery(
                    'SELECT s FROM App\Entity\Sanction s WHERE s.date_insert = :today AND s.vigie = :vigie'
                )
                ->setParameter('today', $today->format('Y-m-d'))
                ->setParameter('vigie', $vigie)
                ->getResult();
            }
        }
    }

    public function get_sanction_month($matricule = null, $roles = null, $all = false): array
    {
        if (!$all) {
            $start = new \DateTime('first day of this month 00:00:00');
            $end = new \DateTime('last day of this month 23:59:59');

            if ($roles == 'RH') {
                return $this->getEntityManager()->createQuery(
                    'SELECT s FROM App\Entity\Sanction s 
                    WHERE s.date_insert BETWEEN :start AND :end'
                )
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getResult();
            } else {
                $vigie = $matricule ? $this->getVigieByMatricule($matricule) : null;

                return $this->getEntityManager()->createQuery(
                    'SELECT s FROM App\Entity\Sanction s 
                    WHERE s.date_insert BETWEEN :start AND :end AND s.vigie = :vigie'
                )
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->setParameter('vigie', $vigie)
                ->getResult();
            }
        }
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
