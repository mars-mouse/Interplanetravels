<?php

namespace App\Repository;

use App\Entity\ImageDestination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageDestination|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageDestination|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageDestination[]    findAll()
 * @method ImageDestination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageDestinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageDestination::class);
    }

    // /**
    //  * @return ImageDestination[] Returns an array of ImageDestination objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageDestination
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
