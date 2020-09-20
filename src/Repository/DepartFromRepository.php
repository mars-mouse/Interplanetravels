<?php

namespace App\Repository;

use App\Entity\DepartFrom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DepartFrom|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepartFrom|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepartFrom[]    findAll()
 * @method DepartFrom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartFromRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepartFrom::class);
    }

    // /**
    //  * @return DepartFrom[] Returns an array of DepartFrom objects
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
    public function findOneBySomeField($value): ?DepartFrom
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
