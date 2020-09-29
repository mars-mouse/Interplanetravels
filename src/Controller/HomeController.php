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


        // Pour la homepage, on a besoin de la liste des promotions et de la liste des voyages
        // $promotions = $promotionRepository->findAll();
        // $travels = $travelRepository->findAll();

        $seeMore = false;
        if (count($promotionRepository->findActivePromotions()) > 3) {
            $seeMore = true;
        }

        $validPromotions = $promotionRepository->findThreeActivePromotions();

        /*
        $validPromotions = [];
        // La promo doit être actuelle
        foreach ($promotions as $promotion) {
            if ($promotion->isPromotionActive()) {
                $validPromotions[] = $promotion;
            }
        }

        // on veut afficher les promos qui vont bientôt expirer en premier
        usort($validPromotions, function ($promA, $promB) {
            if ($promA->getDateEnd() < $promB->getDateEnd()) {
                return -1;
            }

            if ($promA->getDateEnd() === $promB->getDateEnd()) {
                return 0;
            }

            return 1;
        });

        // on ne veut afficher que les 3 premières promo
        // et afficher "See more" s'il y en avait plus que 3
        $seeMore = false;
        if (count($validPromotions) > 3) {
            $seeMore = true;
            $validPromotions = array_slice($validPromotions, 0, 3);
        }
        */

        // La page en cours est donnée en GET
        $page = $request->query->get('page', 1);

        if (!is_numeric($page)) {
            $page = 1;
        }

        // OrderBy est donné en GET
        $orderBy = $request->query->get('order', 'id');

        $validTravels = $travelRepository->findSixAvailable($page, $orderBy);

        $maxPages = ceil($validTravels->count() / 6);

        $page = min($page, $maxPages);

        // s'il y a plus de 7 pages, n'afficher que les pages autour + premier dernier
        // < 1 2 3 4 5 6 7 >
        // < 1 ... 12 13 14 15 16 ... 82 >

        // si $maxPages <= 7
        //      "<", boucle de 1 à $maxPages compris, ">"
        // si $maxPages > 7
        //      "<", "1", 5 pages , "last", ">"
        // quelles 5 pages? 
        // si $page < 3 (1+2)
        //      5 pages = 2 à 6
        // si $page > last - 2
        //      5 pages = last - 5 à last - 1
        // sinon 
        //      5 pages = $page - 2 à $page + 2

        // si $maxPages == 1, dans le twig template on dit qu'on l'affiche pas

        // test values
        // $page = 5;
        // $maxPages = 18;

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
            // for ($i = $maxPages - 5; $i <= $maxPages; $i++) {
            //     $range[] = $i;
            // }
        } else {
            // < 1 ... 12 13 14 15 16 ... 82 >
            $range = [1, '...'];
            $range = array_merge($range, range($page - 2, $page + 2));
            $range = array_merge($range, ['...', $maxPages]);

            // $range = [1, '...'];
            // for ($i = $page - 2; $i <= $page + 2; $i++) {
            //     $range[] = $i;
            // }
            // $range[] = '...';
            // $range[] = $maxPages;
        }

        /*
        $validTravels = [];
        foreach ($travels as $travel) {
            // quand un voyage n'est plus disponible, on met les max_places à 0
            // on ne veut donc pas afficher les voyages non-disponibles
            if ($travel->getMaxPlaces() > 0) {
                $validTravels[] = $travel;
            }
        }
        */

        return $this->render('index.html.twig', [
            'promotions_list' => $validPromotions,
            'see_more_promotions' => $seeMore,
            'travels_list' => $validTravels,
            'this_page' => $page,
            'max_pages' => $maxPages,
            'range' => $range,
        ]);
    }
}