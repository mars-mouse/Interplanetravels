<?php

namespace App\Controller;

use App\Repository\DepartFromRepository;
use App\Repository\DestinationRepository;
use App\Repository\ItineraryRepository;
use App\Repository\TransportRepository;
use App\Repository\TravelDateRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/backoffice", name="backoffice_")
 */
class BackofficeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('backoffice/index.html.twig');
    }

    /**
     * @Route("/travels", name="travels")
     */
    public function manageTravels(
        TravelRepository $travelRepository,
        TravelDateRepository $travelDateRepository,
        ItineraryRepository $itineraryRepository,
        DestinationRepository $destinationRepository,
        DepartFromRepository $departFromRepository,
        TransportRepository $transportRepository
    ) {
        $destinations = $destinationRepository->findAll();
        $transports = $transportRepository->findAll();
        $departFrom = $departFromRepository->findAll();

        return $this->render('backoffice/travels.html.twig', [
            'destinations_list' => $destinations,
            'transports_list' => $transports,
            'depart_from_list' => $departFrom,
        ]);
    }
}
