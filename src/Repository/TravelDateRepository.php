<?php

namespace App\Repository;

use App\Entity\Travel;
use App\Entity\TravelDate;
use DateTime;
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

    /**
     * Renvoie le montant de la promotion sur le voyage concerné par la TravelDate donnée
     */
    public function findPromotionAmount(TravelDate $travelDate)
    {
        return $this->createQueryBuilder('td')
            ->select('p.amount')
            ->innerJoin('td.travel', 't')
            ->innerJoin('t.promotion', 'p')
            ->andWhere('td.id = :tdate')
            ->setParameter('tdate', $travelDate->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie les TravelDates valable à la date actuelle pour le Travel donné
     */
    public function findAvailableByTravel(Travel $travel)
    {
        $currentDate = new DateTime();

        return $this->createQueryBuilder('td')
            ->andWhere('td.travel = :tr')
            ->setParameter('tr', $travel->getId())
            ->andWhere('td.dateDeparture > :cd')
            ->setParameter('cd', $currentDate)
            ->getQuery()
            ->getResult();
    }
}
