<?php

namespace App\Repository;

use App\Entity\ImageTravel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageTravel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageTravel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageTravel[]    findAll()
 * @method ImageTravel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageTravelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageTravel::class);
    }

    // /**
    //  * @return ImageTravel[] Returns an array of ImageTravel objects
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
    public function findOneBySomeField($value): ?ImageTravel
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
