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
}
