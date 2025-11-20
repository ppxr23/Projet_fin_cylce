<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Vigie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Récupère l'objet Vigie lié à un utilisateur via son matricule
     */
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

    /**
     * Récupère tous les utilisateurs actifs, avec filtre par vigie et rôle
     */
    public function get_all_user_actif($matricule = null, $roles = null, $all = false): array
    {
        $em = $this->getEntityManager();

        if (!$all) {
            $vigie = $matricule ? $this->getVigieByMatricule($matricule) : null;

            if ($roles === 'MANAGER') {
                $qb = $em->createQueryBuilder()
                    ->select('u')
                    ->from(User::class, 'u')
                    ->where('u.statut = true')
                    ->andWhere('u.vigie = :vigie')
                    ->andWhere('u.roles LIKE :role')
                    ->orderBy('u.matricule', 'ASC')
                    ->setParameter('vigie', $vigie)
                    ->setParameter('role', '%COLLABORATEUR%');

                return $qb->getQuery()->getResult();
            } elseif ($roles === 'COLLABORATEUR') {
                $qb = $em->createQueryBuilder()
                    ->select('u')
                    ->from(User::class, 'u')
                    ->where('u.statut = true')
                    ->andWhere('u.vigie = :vigie')
                    ->andWhere('u.roles LIKE :role')
                    ->orderBy('u.matricule', 'ASC')
                    ->setParameter('vigie', $vigie)
                    ->setParameter('role', '%MANAGER%');

                return $qb->getQuery()->getResult();
            }

            $qb = $em->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.statut = true')
                ->andWhere('u.matricule != :matricule')
                ->orderBy('u.matricule', 'ASC')
                ->setParameter('matricule', $matricule);

            return $qb->getQuery()->getResult();
        }

        $qb = $em->createQueryBuilder()
            ->select('u, v')
            ->from(User::class, 'u')
            ->leftJoin('u.vigie', 'v')
            ->where('u.matricule != :matricule')
            ->orderBy('u.matricule', 'ASC')
            ->setParameter('matricule', $matricule);

        return $qb->getQuery()->getResult();
    }

    public function get_anomalie($matricule = null, $roles = null): array
    {
        $cnx = $this->getEntityManager()->getConnection();

        $start = new \DateTime('first day of this month 00:00:00');
        $end   = new \DateTime('last day of this month 23:59:59');

        if ($roles == 'RH') {
            $sql = "WITH anomalie AS(
                        SELECT matricule, COUNT(id) AS number_anomalie, 1 AS type_anomalie FROM sanction 
                        WHERE date_insert BETWEEN :start AND :end
                        GROUP BY matricule
                        UNION ALL
                        SELECT matricule, COUNT(id) AS number_anomalie, 2 AS type_anomalie FROM retard 
                        WHERE date BETWEEN :start AND :end
                        GROUP BY matricule
                        UNION ALL
                        SELECT matricule, COUNT(id) AS number_anomalie, 3 AS type_anomalie FROM absence 
                        WHERE deb_abs::date BETWEEN :start AND :end
                        GROUP BY matricule
                    )
                    SELECT anomalie.*,  
                        CASE number_anomalie
                            WHEN 4 THEN 1
                            ELSE 2
                        END AS degree,
                        users.name,
                        users.firstname
                    FROM anomalie
                    INNER JOIN users ON users.matricule = anomalie.matricule
                    WHERE number_anomalie > 3
                    ORDER BY number_anomalie DESC, anomalie.matricule   ";

            $stmt = $cnx->prepare($sql);
            $stmt->bindValue('start', $start->format('Y-m-d H:i:s'));
            $stmt->bindValue('end', $end->format('Y-m-d H:i:s'));
        } else {
            $vigie = $matricule ? $this->getVigieByMatricule($matricule) : null;

            $sql = "WITH anomalie AS(
                        SELECT matricule, COUNT(id) AS number_anomalie, 1 AS type_anomalie FROM sanction 
                        WHERE date_insert BETWEEN :start AND :end
                        GROUP BY matricule
                        UNION ALL
                        SELECT matricule, COUNT(id) AS number_anomalie, 2 AS type_anomalie FROM retard 
                        WHERE date BETWEEN :start AND :end
                        GROUP BY matricule
                        UNION ALL
                        SELECT matricule, COUNT(id) AS number_anomalie, 3 AS type_anomalie FROM absence 
                        WHERE deb_abs::date BETWEEN :start AND :end
                        GROUP BY matricule
                    )
                    SELECT anomalie.*,  
                        CASE number_anomalie
                            WHEN 4 THEN 1
                            ELSE 2
                        END AS degree,
                        users.name,
                        users.firstname,
                        users.vigie
                    FROM anomalie
                    INNER JOIN users ON users.matricule = anomalie.matricule
                    WHERE number_anomalie > 3 AND users.vigie = :vigie
                    ORDER BY number_anomalie DESC, anomalie.matricule";

            $stmt = $cnx->prepare($sql);
            $stmt->bindValue('start', $start->format('Y-m-d H:i:s'));
            $stmt->bindValue('end', $end->format('Y-m-d H:i:s'));
            $stmt->bindValue('vigie', $vigie ? $vigie->getId() : null);
        }

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
