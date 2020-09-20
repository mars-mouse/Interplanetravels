<?php

namespace App\Repository;

use App\Entity\TravelDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TravelDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelDate[]    findAll()
 * @method TravelDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravelDate::class);
    }

    // /**
    //  * @return TravelDate[] Returns an array of TravelDate objects
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
    public function findOneBySomeField($value): ?TravelDate
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
