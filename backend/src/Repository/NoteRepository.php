<?php

namespace App\Repository;

use App\Entity\Note;
use App\Entity\Vigie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
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


    public function get_all_notes(): array 
    {
        $cnx = $this->getEntityManager()->getConnection();

        $start = new \DateTime('first day of this month 00:00:00');
        $end   = new \DateTime('last day of this month 23:59:59');

        $sql = "
            SELECT 
                vigie.name,
                ROUND(AVG(note.note)::numeric, 2) AS moyenne
            FROM note
            INNER JOIN users ON note.matricule = users.matricule
            INNER JOIN vigie ON vigie.id = users.vigie
            WHERE note.date BETWEEN :start AND :end
            GROUP BY vigie.id
            ORDER BY vigie.id
        ";

        $stmt = $cnx->prepare($sql);
        $stmt->bindValue('start', $start->format('Y-m-d H:i:s'));
        $stmt->bindValue('end',   $end->format('Y-m-d H:i:s'));

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function get_all_notes_pers(): array 
    {   
        $cnx = $this->getEntityManager()->getConnection();
        
        $start = new \DateTime('first day of this month 00:00:00');
        $end   = new \DateTime('last day of this month 23:59:59');

        $sql = "
            SELECT 
                users.matricule,
                users.name,
                users.firstname,
                ROUND(AVG(note.note)::numeric, 2) AS moyenne
            FROM note
            INNER JOIN users ON note.matricule = users.matricule
            WHERE note.date BETWEEN :start AND :end
            GROUP BY users.matricule,users.name,users.firstname
            ORDER BY users.matricule,users.name,users.firstname
        ";

        $stmt = $cnx->prepare($sql);
        $stmt->bindValue('start', $start->format('Y-m-d H:i:s'));
        $stmt->bindValue('end',   $end->format('Y-m-d H:i:s'));

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function get_all_notes_team($matricule = null, $roles = null): array 
    {
        $vigie = $matricule ? $this->getVigieByMatricule($matricule) : null;
        
        $cnx = $this->getEntityManager()->getConnection();
        
        $start = new \DateTime('first day of this month 00:00:00');
        $end   = new \DateTime('last day of this month 23:59:59');

        $sql = "
            SELECT 
                users.firstname,
                ROUND(AVG(note.note)::numeric, 2) AS moyenne
            FROM note
            INNER JOIN users ON note.matricule = users.matricule
            WHERE note.date BETWEEN :start AND :end AND users.vigie = :vigie
            GROUP BY users.firstname
            ORDER BY users.firstname
        ";

        $stmt = $cnx->prepare($sql);
        $stmt->bindValue('start', $start->format('Y-m-d H:i:s'));
        $stmt->bindValue('end',   $end->format('Y-m-d H:i:s'));
        $stmt->bindValue('vigie',   $vigie);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    //    /**
    //     * @return Note[] Returns an array of Note objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Note
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
