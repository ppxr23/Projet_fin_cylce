<?php

namespace App\Repository;

use App\Entity\Feedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feedback>
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function getVigieByMatricule(string $matricule): ?int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT vigie FROM users WHERE matricule = :matricule";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('matricule', $matricule);

        $vigieId = $stmt->executeQuery()->fetchOne();

        return $vigieId ? (int)$vigieId : null;
    }

    public function get_feedback($matricule = null, $roles = null) :array
    {
        $cnx = $this->getEntityManager()->getConnection();
        
        $start = new \DateTime('first day of this month 00:00:00');
        $end   = new \DateTime('last day of this month 23:59:59');

        if ($roles == 'RH')     
        {
            $sql = "SELECT
                        matricule_concerned AS matricule,
                        commentary AS commentaire,
                        ROUND((critere_1 + critere_2 + critere_3 + critere_4 + critere_5) / 5.0::numeric, 2) AS note,
                        users.name,
                        users.firstname
                    FROM feedback
                    INNER JOIN users ON users.matricule = feedback.matricule_concerned
                    WHERE date_inserted BETWEEN :start AND :end";
            
            $stmt = $cnx->prepare($sql);
        }
        elseif ($roles == 'MANAGER')
        {
            $vigie = $matricule ? $this->getVigieByMatricule($matricule) : null;
            
            $sql = "SELECT
                        matricule_concerned AS matricule,
                        commentary AS commentaire,
                        ROUND((critere_1 + critere_2 + critere_3 + critere_4 + critere_5) / 5.0::numeric, 2) AS note,
                        users.name,
                        users.firstname
                    FROM feedback
                    INNER JOIN users ON users.matricule = feedback.matricule_concerned
                    WHERE date_inserted BETWEEN :start AND :end AND (vigie = :vigie OR matricule_concerned = :matricule)";
            
            $stmt = $cnx->prepare($sql);
            $stmt->bindValue('matricule', $matricule);
            $stmt->bindValue('vigie', $vigie);
        }
        else
        {
            $sql = "SELECT
                        matricule_concerned AS matricule,
                        commentary AS commentaire,
                        ROUND((critere_1 + critere_2 + critere_3 + critere_4 + critere_5) / 5.0::numeric, 2) AS note,
                        users.name,
                        users.firstname
                    FROM feedback
                    INNER JOIN users ON users.matricule = feedback.matricule_concerned
                    WHERE date_inserted BETWEEN :start AND :end AND matricule_concerned = :matricule";
            
            $stmt = $cnx->prepare($sql);
            $stmt->bindValue('matricule', $matricule);
        }

        $stmt->bindValue('start', $start->format('Y-m-d H:i:s'));
        $stmt->bindValue('end',   $end->format('Y-m-d H:i:s'));

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    //    /**
    //     * @return Feedback[] Returns an array of Feedback objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Feedback
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
