<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Repository\BookingRepository;
use App\Repository\TravelDateRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TravelController extends AbstractController
{
    /**
     * @Route("/travels", name="travels")
     */
    public function travels(TravelRepository $travelRepository)
    {
        $travels = $travelRepository->findAll();

        return $this->render('travel/travels.html.twig', [
            'travels_list' => $travels,
        ]);
    }

    /**
     * @Route("/travel/{id}", name="travel")
     */
    public function travel(Travel $travel, BookingRepository $bookingRepository, TravelDateRepository $travelDateRepository)
    {
        // on récupère les dates du voyage
        $travelDates = $travelDateRepository->findBy(['travel' => $travel]);

        // on fait un tableau du nombre de places réservées par date de voyage
        $booked = [];
        foreach ($travelDates as $travelDate) {
            // s'il n'y a pas de réservation pour cette date, cela renvoie null
            // si c'est null on veut plutôt 0 (0 place réservée)
            $sumBookedPlaces = $bookingRepository->sumBookedPlaces($travelDate) ?? 0;
            // on enregistre dans la clef correspondant à l'id de la date
            $booked[$travelDate->getId()] = $sumBookedPlaces;
        }

        return $this->render('travel/travel.html.twig', [
            'travel' => $travel,
            'booked' => $booked,
        ]);
    }
}