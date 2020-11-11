<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\TravelDate;
use App\Entity\User;
use DateTime;
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
            ->andWhere('td.id = :val')
            ->setParameter('val', $travelDate->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Booking[] Renvoie les Bookings validés à venir de l'utilisateur
     */
    public function comingBookings(User $user)
    {
        $currentDate = new DateTime();

        return $this->createQueryBuilder('b')
            ->innerJoin('b.travelDate', 'td')
            ->andWhere('td.dateDeparture > :cd')
            ->setParameter('cd', $currentDate)
            ->andWhere('b.validated = true')
            ->andWhere('b.user = :u')
            ->setParameter('u', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Booking[] Renvoie les Bookings non-validés à venir de l'utilisateur
     */
    public function pendingBookings(User $user)
    {
        $currentDate = new DateTime();

        return $this->createQueryBuilder('b')
            ->innerJoin('b.travelDate', 'td')
            ->andWhere('td.dateDeparture > :cd')
            ->setParameter('cd', $currentDate)
            ->andWhere('b.validated = false')
            ->andWhere('b.user = :u')
            ->setParameter('u', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Booking[] Renvoie les anciens Bookings validés de l'utilisateur
     */
    public function pastBookings(User $user)
    {
        $currentDate = new DateTime();

        return $this->createQueryBuilder('b')
            ->innerJoin('b.travelDate', 'td')
            ->andWhere('td.dateDeparture <= :cd')
            ->setParameter('cd', $currentDate)
            ->andWhere('b.validated = true')
            ->andWhere('b.user = :u')
            ->setParameter('u', $user)
            ->getQuery()
            ->getResult();
    }
}