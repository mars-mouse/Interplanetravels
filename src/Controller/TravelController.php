<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Entity\Travel;
use App\Repository\BookingRepository;
use App\Repository\TravelDateRepository;
use App\Repository\TravelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;

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
    public function travel(
        Travel $travel,
        BookingRepository $bookingRepository,
        TravelDateRepository $travelDateRepository,
        Request $request,
        Security $security,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        // on récupère les TravelDates encore valable aujourd'hui pour le Travel
        $travelDates = $travelDateRepository->findAvailableByTravel($travel);

        // on fait un tableau du nombre de places réservées par date de voyage
        $booked = [];
        foreach ($travelDates as $travelDate) {
            // s'il n'y a pas de réservation pour cette date, cela renvoie null
            // si c'est null on veut plutôt 0 (0 place réservée)
            $sumBookedPlaces = $bookingRepository->sumBookedPlaces($travelDate) ?? 0;
            // on enregistre dans la clef correspondant à l'id de la date
            $booked[$travelDate->getId()] = $sumBookedPlaces;
        }

        // Si on a appuyé sur le bouton Bookmark
        // Enregistrer le Travel dans un nouveau Bookmark du User
        // Afficher un message de succès
        if ($request->request->has('bookmark')) {
            $connectedUser = $security->getUser();
            $email = $connectedUser->getUsername();
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                // fail authentication with a custom error
                throw new CustomUserMessageAuthenticationException('Unknown User.');
            }

            $bookmark = new Bookmark();
            $bookmark->setUser($user);
            $bookmark->setTravel($travel);
            $bookmark->setAlert(true);  // par défaut en attendant un prompt

            $entityManager->persist($bookmark);
            $entityManager->flush();

            $this->addFlash('bookmarkSuccess', 'Successfully bookmarked this travel.');
        }

        return $this->render('travel/travel.html.twig', [
            'travel' => $travel,
            'travelDate_list' => $travelDates,
            'booked' => $booked,
        ]);
    }
}