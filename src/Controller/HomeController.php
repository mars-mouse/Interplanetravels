<?php

namespace App\Controller;

use App\Repository\PromotionRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(
        UrlGeneratorInterface $urlGenerator,
        Security $security,
        PromotionRepository $promotionRepository,
        TravelRepository $travelRepository,
        Request $request
    ) {
        // Si on se connecte en tant qu'Admin sur la homepage, on est renvoyé vers le backoffice
        if ($security->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($urlGenerator->generate('backoffice_index'));
        }

        // S'il y a 3+ promotions au même moment, il faut afficher "See More Promotions"
        $seeMore = false;
        if (count($promotionRepository->findActivePromotions()) > 3) {
            $seeMore = true;
        }
        // On ne garde que les 3 promotions qui terminent le plus tôt
        $validPromotions = $promotionRepository->findThreeActivePromotions();

        // Pour les Travels
        // Pagination
        // La page en cours est donnée en GET
        $page = $request->query->get('page', 1);

        if (!is_numeric($page) || $page <= 0) {
            $page = 1;
        }

        // OrderBy est donné en GET
        $orderBy = $request->query->get('order', 'name');

        // On récupère les 6 Travels de la page en cours
        $validTravels = $travelRepository->findSixAvailable($page, $orderBy);

        // La méthode count() de Paginator permet de connaître le nombre maximum de résultat
        $maxPages = ceil($validTravels->count() / 6);

        // On vérifie que la page en cours est valide
        if ($page > $maxPages) {
            // La page en cours est ramenée à son maximum
            $page = $maxPages;
            // On redemande les Travels qui correspondent à cette page
            $validTravels = $travelRepository->findSixAvailable($page, $orderBy);
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

        return $this->render('index.html.twig', [
            'promotions_list' => $validPromotions,
            'see_more_promotions' => $seeMore,
            'travels_list' => $validTravels,
            'this_page' => $page,
            'max_pages' => $maxPages,
            'range' => $range,
            'filtres' => $filtres,
            'order' => $orderBy,
        ]);
    }
}