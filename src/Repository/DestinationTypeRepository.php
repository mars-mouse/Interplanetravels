<?php

namespace App\Repository;

use App\Entity\DestinationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DestinationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DestinationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DestinationType[]    findAll()
 * @method DestinationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DestinationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DestinationType::class);
    }

    // /**
    //  * @return DestinationType[] Returns an array of DestinationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DestinationType
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
