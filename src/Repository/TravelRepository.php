<?php

namespace App\Repository;

use App\Entity\Travel;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Travel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Travel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Travel[]    findAll()
 * @method Travel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Travel::class);
    }

    /**
     * Récupérer la page n° $currentPage des pages de Travel qui s'affichent 6 par 6 
     * @return Paginator
     */
    public function findSixAvailable($currentPage, $orderBy)
    {
        // ($page - 1) * $limit
        // $request->query->get('page', 1)
        if (!in_array($orderBy, ['name', 'price', 'dateDeparture'])) {
            $orderBy = 'id';
        }

        if ($orderBy === "dateDeparture") {
            $orderBy = 'td.' . $orderBy;
        } else {
            $orderBy = 't.' . $orderBy;
        }

        $query = $this->createQueryBuilder('t')
            ->andWhere('t.maxPlaces > 0')
            ->innerJoin('t.travelDates', 'td')
            ->andWhere('td.dateDeparture > CURRENT_DATE()')
            ->orderBy($orderBy, 'ASC');

        $paginator = $this->paginate($query, $currentPage, 6);

        return $paginator;
    }

    /**
     * Paginator Helper
     *
     * Pass through a query object, current page & limit
     * the offset is calculated from the page and limit
     * returns an `Paginator` instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `6` travels)
     *     $paginator->count() # Count of ALL posts (ie: `20` travels)
     *     $paginator->getIterator() # ArrayIterator
     *
     * @param Doctrine\ORM\Query $dql   DQL Query Object
     * @param integer            $page  Current page (defaults to 1)
     * @param integer            $limit The total number per page (defaults to 6)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function paginate($dql, $page = 1, $limit = 6)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))  // Offset
            ->setMaxResults($limit);                // Limit

        return $paginator;
    }
}