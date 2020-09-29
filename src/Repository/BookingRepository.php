<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\TravelDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * @return Booking[] Returns an array of Booking objects
     */
    public function findByTravelId($travelId)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.travelDate', 'td')
            ->andWhere('td.travel = :val')
            ->setParameter('val', $travelId)
            ->getQuery()
            ->getResult();
    }



    /**
     * @return int Renvoie nombre de places réservées
     */
    public function sumBookedPlaces(TravelDate $travelDate)
    {
        return $this->createQueryBuilder('b')
            ->select('SUM(b.numberPlaces)')
            ->andWhere('b.validated = true')
            ->innerJoin('b.travelDate', 'td')
            ->andWhere('td.travel = :val')
            ->setParameter('val', $travelDate->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}