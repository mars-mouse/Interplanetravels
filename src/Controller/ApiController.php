<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use App\Repository\ItineraryRepository;
use App\Repository\TravelDateRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/travels", name="api_travels")
     */
    public function apiTravels(
        TravelRepository $travelRepository,
        TravelDateRepository $travelDateRepository,
        DestinationRepository $destinationRepository,
        ItineraryRepository $itineraryRepository
    ) {
        $allDestinationsJSON = [];

        $destinations = $destinationRepository->findAll();

        // pour chaque Destination
        foreach ($destinations as $destination) {

            // on récupère tous les Itinerary qui passent par cette Destination
            $itineraries = $itineraryRepository->findBy(['destination' => $destination->getId()]);

            $travels = [];
            // pour chaque Itinerary
            foreach ($itineraries as $itinerary) {
                // on récupère le Travel qui correspond à cet Itinerary
                $travels[] = $travelRepository->findOneBy(['id' => $itinerary->getTravel()]);
            }

            $travelsJSON = [];
            // pour chaque Travel
            foreach ($travels as $travel) {
                $datesDeparture = [];
                $datesReturn = [];

                $travelDates = $travelDateRepository->findBy(['travel' => $travel->getId()]);
                // pour chaque TravelDate correspondant au Travel
                foreach ($travelDates as $travelDate) {
                    $datesDeparture[] = $travelDate->getDateDeparture();
                    $datesReturn[] = $travelDate->getDateReturn();
                }

                // on prépare les données importantes du Travel
                $tempObject = [
                    'id' => $travel->getId(),
                    'name' => $travel->getName(),
                    'datesDeparture' => $datesDeparture,
                    'datesReturn' => $datesReturn,
                ];

                // on vérifie que ce Travel n'a pas déjà été ajouté
                $exists = 0;
                foreach ($travelsJSON as $tableEntry) {
                    if ($tableEntry['id'] === $tempObject['id']) {
                        $exists = 1;
                    }
                }
                if (!$exists) {
                    // on ajoute les données du Travel au tableau des données de tous les Travel liés à la Destination
                    $travelsJSON[] = $tempObject;
                }
            }

            // on place le tableau des Travel dans les données de la Destination
            $destinationJSON = [
                'id' => $destination->getId(),
                'name' => $destination->getName(),
                'travels' => $travelsJSON,
            ];

            // on ajoute maintenant les données de cette Destination au tableau de toutes les résultats
            $allDestinationsJSON[] = $destinationJSON;
        }

        return new JsonResponse($allDestinationsJSON);
    }
}