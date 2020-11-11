<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\SavedPayment;
use App\Form\PaymentFormType;
use App\Repository\BookingRepository;
use App\Repository\BookmarkRepository;
use App\Repository\SavedPaymentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile", name="profile")
     */
    public function profile(
        Security $security,
        UserRepository $userRepository,
        BookingRepository $bookingRepository,
        Request $request,
        SavedPaymentRepository $savedPaymentRepository,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ) {
        $connectedUser = $security->getUser();
        $email = $connectedUser->getUsername();
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Unknown User.');
        }

        // Création des listes Booked et Pending
        $comingBookings = $bookingRepository->comingBookings($user);
        $pendingBookings = $bookingRepository->pendingBookings($user);

        // on fait un tableau du nombre de places réservées par date de voyage
        $booked = [];
        foreach ($pendingBookings as $booking) {
            // s'il n'y a pas de réservation pour cette date, cela renvoie null
            // si c'est null on veut plutôt 0 (0 place réservée)
            $sumBookedPlaces = $bookingRepository->sumBookedPlaces($booking->getTravelDate()) ?? 0;
            // on enregistre dans la clef correspondant à l'id de la date
            $booked[$booking->getTravelDate()->getId()] = $sumBookedPlaces;
        }

        // Pour la partie "saved payments", on crée le formulaire de paiement
        $payment = new Payment();
        $form = $this->createForm(PaymentFormType::class, $payment);
        $form->handleRequest($request);

        // Si le formulaire est envoyé (nouveau détails de paiement ou édition d'un existant)
        if ($form->isSubmitted()) {
            // Vérifier quel bouton a été utilisé (Save ou Delete)
            $isSaving = $request->request->has('savePayment');
            $isDeleting = $request->request->has('deletePayment');
            $name = $request->request->get('savedPaymentName');

            if ($isSaving === true && $form->isValid()) {
                // Propriétés du Payment données par le formulaire :
                // $addressBilling;
                // $addressDelivery;
                // $cardNumber;
                // $cardType;
                // $crypto;
                // $dateExpiration;
                // $fullName;

                // Propriétés relationnelles
                // $booking;    (reste null)
                // $savedPayment; 

                // Propriété du SavedPayment donnée par le formulaire
                // $name; (à récupérer de la $request)
                // $name = $request->request->get('savedPaymentName');

                // On vérifie la validité du name
                if ($name !== '' && $name !== 'New' && $savedPaymentRepository->findOneByName($name) === null) {
                    // Nom valide, enregistrer le Payment et le SavedPayment
                    $savedPayment = new SavedPayment();
                    $savedPayment->setName($name);
                    $savedPayment->setUser($user);
                    $savedPayment->setPayment($payment); // vient du formulaire

                    $payment->setSavedPayment($savedPayment);

                    $entityManager->persist($payment);
                    $entityManager->persist($savedPayment);
                    $entityManager->flush();

                    // Le SavedPayment a été enregistré, on réinitialise la page
                    // pour éviter de proposer de l'enregistrer plusieurs
                    $this->addFlash('saveSuccess', 'The payment details have been successfully saved.');
                    return new RedirectResponse($urlGenerator->generate('profile', ['_fragment' => 'savedPaymentsSection']));
                } else {
                    // Nom non-valide, message d'erreur
                    $this->addFlash('nameError', 'Invalid name. It cannot be an existing name or the name "New".');
                }
            } elseif ($isDeleting === true) {
                // Vérifier que le nom est valide
                // $name = $request->request->get('savedPaymentName');
                $savedPaymentToDelete = $savedPaymentRepository->findOneByName($name);

                if ($savedPaymentToDelete !== null) {
                    // Récupérer le Payment associé et le retirer
                    $paymentToDelete = $savedPaymentToDelete->getPayment();
                    $entityManager->remove($paymentToDelete);

                    // Retirer l'entrée du savedPayment
                    $entityManager->remove($savedPaymentToDelete);
                    $entityManager->flush();
                }

                // Si on revient au return $this->render, le formulaire sera vide avec des erreurs
                // sur les champs comme s'ils étaient non-valides.
                // Pour éviter ça, on redirige vers la même route pour réinitialiser la page
                // et refaire la liste déroulante des SavedPayments
                return new RedirectResponse($urlGenerator->generate('profile', ['_fragment' => 'savedPaymentsSection']));
            }
        }

        return $this->render('profile/profile.html.twig', [
            'booked_list' => $comingBookings,
            'pending_list' => $pendingBookings,
            'booked' => $booked,
            'paymentForm' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/bookings", name="bookings")
     */
    public function bookings(
        Security $security,
        UserRepository $userRepository,
        BookingRepository $bookingRepository
    ) {
        $connectedUser = $security->getUser();
        $email = $connectedUser->getUsername();
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Unknown User.');
        }

        // Création des listes Booked et Pending
        $comingBookings = $bookingRepository->comingBookings($user);
        $pendingBookings = $bookingRepository->pendingBookings($user);

        // on fait un tableau du nombre de places réservées par date de voyage
        $booked = [];
        foreach ($pendingBookings as $booking) {
            // s'il n'y a pas de réservation pour cette date, cela renvoie null
            // si c'est null on veut plutôt 0 (0 place réservée)
            $sumBookedPlaces = $bookingRepository->sumBookedPlaces($booking->getTravelDate()) ?? 0;
            // on enregistre dans la clef correspondant à l'id de la date
            $booked[$booking->getTravelDate()->getId()] = $sumBookedPlaces;
        }

        // Création de l'historique des voyages effectués (date passée + confirmé)
        $pastBookings = $bookingRepository->pastBookings($user);


        return $this->render('profile/bookings.html.twig', [
            'booked_list' => $comingBookings,
            'pending_list' => $pendingBookings,
            'booked' => $booked,
            'pastBookings_list' => $pastBookings,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/bookmarks", name="bookmarks")
     */
    public function bookmarks(Security $security, UserRepository $userRepository, BookmarkRepository $bookmarkRepository)
    {
        $connectedUser = $security->getUser();
        $email = $connectedUser->getUsername();
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Unknown User.');
        }

        // Liste de tous les bookmarks de l'utilisateur
        $bookmarks = $bookmarkRepository->findBy(['user' => $user]);

        return $this->render('profile/bookmarks.html.twig', [
            'bookmarks_list' => $bookmarks,
        ]);
    }
}