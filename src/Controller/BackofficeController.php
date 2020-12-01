<?php

namespace App\Controller;

use App\Entity\Itinerary;
use App\Entity\Travel;
use App\Entity\TravelDate;
use App\Form\TravelDateFormType;
use App\Form\TravelFormType;
use App\Repository\DepartFromRepository;
use App\Repository\DestinationRepository;
use App\Repository\ItineraryRepository;
use App\Repository\TransportRepository;
use App\Repository\TravelDateRepository;
use App\Repository\TravelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/backoffice", name="backoffice_")
 */
class BackofficeController extends AbstractController
{
    /**
     * Fonction de comparaison entre les dateArrival de deux Itinerary.
     * Si les dateArrival sont les mêmes, comparaison des dateDeparture.
     * Utilisé avec usort()
     */
    private function dateComparison(Itinerary $itinerary1, Itinerary $itinerary2)
    {
        $a = $itinerary1->getDayArrival();
        $b = $itinerary2->getDayArrival();
        if ($a === $b) {
            $c = $itinerary1->getDayDeparture();
            $d = $itinerary2->getDayDeparture();
            if ($c === $d) {
                return 0;
            }
            return ($c < $d) ? -1 : 1;
        }
        return ($a < $b) ? -1 : 1;
    }

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

    /**
     * @Route("/add_travel", name="add_travel")
     */
    public function addTravel(Request $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $travel = new Travel();
        $form = $this->createForm(TravelFormType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /// Travel
            // Propriétés venant du formulaire :
            // $name, $price, $description, $departFrom
            //
            // Propriétés ajoutées plus tard, séparemment :
            // $promotions, $images, $bookmarks, $reviews
            //
            // Propriétés restantes :
            // $travelDates, $itineraries (vient du formulaire dynamique), maxPlaces
            $travel->setMaxPlaces(0); // Le Travel est indisponible pour l'instant

            /// Itinerary (dans une array)
            // Propriétés venant du formulaire dynamique:
            // $dayArrival, $dayDeparture, $destination, $transport, $travel

            // On doit vérifier que l'enchaînement des $dayArrival et $dayDeparture fait sens :
            // - $dayArrival doit être plus petit que $dayDeparture
            // - Egalement on prend donc les $itineraries, on les organise par ordre de $dayArrival
            //   puis on vérifie que chaque $dayDeparture est plus petit que le $dayArrival suivant
            $itineraries = $travel->getItineraries();

            // Test dayArrival avant dayDeparture
            $hasIndividualError = false;
            foreach ($itineraries as $itinerary) {
                if ($itinerary->getDayArrival() > $itinerary->getDayDeparture()) {
                    $hasIndividualError = true;
                    $this->addFlash('errorItinerary', "You can't arrive on a day after the departure.");
                    continue;
                }
            }

            // Test plages de dates qui se recoupent
            $itinerariesCount = count($itineraries);
            $hasConflictingDates = false;
            if ($itinerariesCount > 1) {
                // Il faut une array pour le tri
                $itiArray = [];
                foreach ($itineraries as $itinerary) {
                    $itiArray[] = $itinerary;
                }

                // Tri par dateArrival
                usort($itiArray, [$this, "dateComparison"]);

                // On regarde si pour chaque Itinerary de la liste, les dates de l'Itinerary suivant sont compatibles.
                // On doit donc s'arrêter un Itinerary avant la fin.
                for ($i = 0; $i < $itinerariesCount - 1; $i++) {
                    // La date de départ ne peut être postérieur à la date d'arrivée à la destination suivante
                    if ($itiArray[$i]->getDayDeparture() > $itiArray[$i + 1]->getDayArrival()) {
                        $hasConflictingDates = true;
                        $this->addFlash('errorItinerary', "Some destinations have dates in conflict with one another.");
                        $i = $itinerariesCount;
                    }
                }

                // Pour un meilleur affichage plus tard, reprenons le tableau trié pour enregistrer les Itinerary dans l'ordre
                // On vide les itineraries du $travel
                foreach ($travel->getItineraries() as $itinerary) {
                    $travel->removeItinerary($itinerary);
                }
                // Puis on les remets dans l'ordre
                for ($i = 0; $i < $itinerariesCount; $i++) {
                    $travel->addItinerary($itiArray[$i]);
                }
            }

            if (!$hasConflictingDates && !$hasIndividualError) {
                // Enregistrer le Travel
                $entityManager->persist($travel);
                $entityManager->flush();
                // Rediriger vers la page du Travel
                return new RedirectResponse($urlGenerator->generate('travel', ['id' => $travel->getId()]));
            }
        }

        return $this->render('backoffice/add_travel.html.twig', [
            'travelForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add_traveldates/{id}", name="add_traveldates")
     */
    public function addTravelDates(Travel $travel, Request $request, EntityManagerInterface $entityManager)
    {
        $travelDate = new TravelDate();
        $form = $this->createForm(TravelDateFormType::class, $travelDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifications : ordre des dates, temps entre les dates qui respecte l'itinéraire
            $travelTime = $travelDate->getDateDeparture()->diff($travelDate->getDateReturn());
            if ($travelTime->invert === 1) {
                $this->addFlash('errorDates', 'The date of return cannot come before the date of departure.');
            } else {
                if ($travelTime->y > 0) {
                    $this->addFlash('errorDates', 'Our travels cannot take years.');
                } else {
                    // Cherchons le dernier jour de depart dans les itinéraires
                    $lastDay = 0;
                    foreach ($travel->getItineraries() as $itinerary) {
                        if ($itinerary->getDayDeparture() > $lastDay) {
                            $lastDay = $itinerary->getDayDeparture();
                        }
                    }

                    // Le temps entre les dates en jours doit être supérieur au jour de départ du dernier itinéraire
                    if ($travelTime->days < $lastDay) {
                        $this->addFlash('errorDates', 'The travel must last at least ' . $lastDay . ' days.');
                    } else {
                        // On peut enregistrer la TravelDate
                        $travelDate->setTravel($travel);
                        $entityManager->persist($travelDate);
                        $entityManager->flush();
                    }
                }
            }
        }

        return $this->render('backoffice/add_traveldates.html.twig', [
            'travel' => $travel,
            'travelDateForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete_traveldate/{id}", name="delete_traveldate")
     */
    public function deleteTravelDate(TravelDate $travelDate, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        // On ne peut pas retirer une TravelDate dont les réservations ont déjà commencé
        $reservations = 0;
        foreach ($travelDate->getBookings() as $booking) {
            if ($booking->getValidated()) {
                $reservations += $booking->getNumberPlace();
            }
        }
        if ($reservations > 0) {
            $this->addFlash('errorDates', 'The travel date you are trying to delete already has ' . $reservations . ' reservations.');
        } else {
            $entityManager->remove($travelDate);
            $entityManager->flush();
            $this->addFlash('successDates', 'Travel date successfully deleted.');
        }

        // Rediriger vers la page des TravelDates
        return new RedirectResponse($urlGenerator->generate(
            'backoffice_add_traveldates',
            ['id' => $travelDate->getTravel()->getId(), '_fragment' => 'traveldates']
        ));
    }

    /**
     *  @Route("/travel/{id}", name="edit_travel")
     */
    public function editTravel(Travel $travel)
    {
        return $this->render('backoffice/travel.html.twig', [
            'travel' => $travel,
        ]);
    }
}