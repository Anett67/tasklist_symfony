<?php

namespace App\Repository;

use App\Entity\Tasklist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tasklist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasklist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasklist[]    findAll()
 * @method Tasklist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tasklist::class);
    }

    // /**
    //  * @return Tasklist[] Returns an array of Tasklist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tasklist
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
