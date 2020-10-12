<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DestinationController extends AbstractController
{
    /**
     * @Route("/destination/{id}", name="destination")
     */
    public function destination(
        Destination $destination,
        TravelRepository $travelRepository,
        Request $request
    ) {
        // cas où aucune destination n'a été trouvée à cet id

        // Pour les Travels
        // Pagination
        // La page en cours est donnée en GET
        $page = $request->query->get('page', 1);

        if (!is_numeric($page) && $page > 0) {
            $page = 1;
        }

        // OrderBy est donné en GET
        $orderBy = $request->query->get('order', 'name');

        // on crée la liste des voyages qui passent par cette destination
        $travelList = $travelRepository->findSixByDestination($page, $orderBy, $destination);
        $maxPages = ceil($travelList->count() / 6);

        // On vérifie que la page en cours est valide
        if ($page > $maxPages) {
            // La page en cours est ramenée à son maximum
            $page = $maxPages;
            // On redemande les Travels qui correspondent à cette page
            $travelList = $travelRepository->findSixByDestination($page, $orderBy, $destination);
        }

        // On veut dans $range une suite de nombres de pages ou des ellipses
        // La structure de $range sera :
        // [Première Page] [...] [2 pages] [Page en cours] [2 pages] [...] [Dernière Page]
        // Les ellipses sont optionnelles
        $range = [];
        if ($maxPages <= 7) {
            // < 1 2 >
            // < 1 2 3 4 5 6 7 >
            for ($i = 1; $i <= $maxPages; $i++) {
                $range[] = $i;
            }
        } elseif ($page <= 4) {
            // < 1 2 3 4 5 6 ... 22 >
            $range = [1, 2, 3, 4, 5, 6, '...', $maxPages];
        } elseif ($page >= $maxPages - 3) {
            // < 1 ... 7 8 9 10 11 12 >
            $range = [1, '...'];
            $range = array_merge($range, range($maxPages - 5, $maxPages));
        } else {
            // < 1 ... 12 13 14 15 16 ... 82 >
            $range = [1, '...'];
            $range = array_merge($range, range($page - 2, $page + 2));
            $range = array_merge($range, ['...', $maxPages]);
        }

        // tableau des noms de filtres
        $filtres = [
            'name' => 'Name',
            'price' => 'Price',
            'dateDeparture' => 'Date',
        ];

        return $this->render('destination/destination.html.twig', [
            'destination' => $destination,
            'travels_list' => $travelList,
            'order' => $orderBy,
            'this_page' => $page,
            'max_pages' => $maxPages,
            'range' => $range,
            'filtres' => $filtres,
        ]);
    }
}