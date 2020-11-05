<?php

namespace App\Repository;

use App\Entity\SavedPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SavedPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method SavedPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method SavedPayment[]    findAll()
 * @method SavedPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavedPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedPayment::class);
    }

    // /**
    //  * @return SavedPayment[] Returns an array of SavedPayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * Renvoie le SavedPayment ayant le nom spécifié ou null si aucun ne correspond
     */
    public function findOneByName($name): ?SavedPayment
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}