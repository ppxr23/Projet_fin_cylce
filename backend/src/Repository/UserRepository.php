<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getVigieByMatricule(string $matricule): ?string
    {
        $result = $this->createQueryBuilder('u')
            ->select('u.vigie')
            ->where('u.matricule = :matricule')
            ->setParameter('matricule', $matricule)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result['vigie'] : null;
    }

    public function get_all_user_actif($matricule = null, $roles = null, $all = false): array
    {
        $em = $this->getEntityManager();

        if (!$all) {
            $vigie = $this->getVigieByMatricule($matricule);

            if ($roles === 'MANAGER') {
                return $em->createQuery(
                    'SELECT u FROM App\Entity\User u 
                     WHERE u.statut = true 
                     AND u.vigie = :vigie 
                     AND u.roles LIKE :role 
                     ORDER BY u.matricule'
                )
                ->setParameter('vigie', $vigie)
                ->setParameter('role', '%COLLABORATEUR%')
                ->getResult();
            }
            else if ($roles === 'COLLABORATEUR') {
                return $em->createQuery(
                    'SELECT u FROM App\Entity\User u 
                     WHERE u.statut = true 
                     AND u.vigie = :vigie 
                     AND u.roles LIKE :role 
                     ORDER BY u.matricule'
                )
                ->setParameter('vigie', $vigie)
                ->setParameter('role', '%MANAGER%')
                ->getResult();
            }

            return $em->createQuery(
                'SELECT u FROM App\Entity\User u 
                 WHERE u.statut = true 
                 AND u.matricule != :matricule
                 ORDER BY u.matricule'
            )
            ->setParameter('matricule', $matricule)
            ->getResult();
        }

        return $em->createQuery(
            'SELECT u FROM App\Entity\User u 
             WHERE u.matricule != :matricule
             ORDER BY u.matricule'
        )
        ->setParameter('matricule', $matricule)
        ->getResult();
    }
}
