<?php

namespace App\Controller;

use DateTime;
use App\Entity\Booking;
use App\Entity\Payment;
use App\Entity\SavedPayment;
use App\Entity\TravelDate;
use App\Form\BookingFormType;
use App\Form\PaymentFormType;
use App\Repository\BookingRepository;
use App\Repository\PaymentRepository;
use App\Repository\SavedPaymentRepository;
use App\Repository\TravelDateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;

class BookingController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/booking/{id}", name="booking")
     */
    public function booking(
        TravelDate $travelDate,
        BookingRepository $bookingRepository,
        TravelDateRepository $travelDateRepository,
        UserRepository $userRepository,
        Request $request,
        Security $security,
        UrlGeneratorInterface $urlGenerator
    ) {
        // Nombre de places déjà réservées
        // s'il n'y a pas de réservation pour cette date, cela renvoie null
        // si c'est null on veut plutôt 0 (0 place réservée)
        $sumBookedPlaces = $bookingRepository->sumBookedPlaces($travelDate) ?? 0;

        $booking = new Booking();
        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // L'objet Booking doit récupérer :
            // $date;
            // $paidAmount;
            // $promotionValue;
            // $numberPlaces; => Récupéré dans le formulaire
            // $validated;
            // $user;
            // $travelDate;

            // Date de création de la réservation, sera mise à jour au paiement
            $booking->setDate(new DateTime());

            // La TravelDate n'est pas donnée par l'utilisateur, on l'a déjà
            $booking->setTravelDate($travelDate);

            // On récupère l'utilisateur connecté
            // D'abord l'objet user security
            $connectedUser = $security->getUser();
            $email = $connectedUser->getUsername();
            // de ces infos on récupère l'entité User correspondante
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                // fail authentication with a custom error
                throw new CustomUserMessageAuthenticationException('Unknown User.');
            }
            $booking->setUser($user);

            // on récupère la promotion s'il y en a une en cours
            $promotions = $travelDateRepository->findPromotionAmount($travelDate);   // renvoie une array de clefs 'amount'
            if ($promotions === []) {
                $promotion = 0;
            } else {
                $promotion = $promotions[0]['amount'];
            }
            $booking->setPromotionValue($promotion);

            // Pour l'instant le montant payé est 0 et le booking n'est pas validé
            $booking->setPaidAmount(0);
            $booking->setValidated(false);

            // On n'a pas besoin du paiement tout de suite

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            // Une fois le Booking créé, on peut aller à la page paiement.
            // Si jamais la connexion est interrompue ici, le Booking apparaitra dans le User profile.
            return new RedirectResponse($urlGenerator->generate('payment', ['id' => $booking->getId()]));
        }

        return $this->render('booking/booking.html.twig', [
            'travelDate' => $travelDate,
            'booked' => $sumBookedPlaces,
            'bookingForm' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/payment/{id}", name="payment")
     */
    public function payment(
        Booking $booking,
        UserRepository $userRepository,
        Security $security,
        UrlGeneratorInterface $urlGenerator,
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        // On doit vérifier que le Booking appartient au User connecté
        //
        // On récupère l'utilisateur connecté
        // D'abord l'objet user security
        $connectedUser = $security->getUser();
        $email = $connectedUser->getUsername();
        // de ces infos on récupère l'entité User correspondante
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Unknown User.');
        }

        // Si le Booking n'appartient pas au User connecté, on redirige vers la homepage
        if ($booking->getUser()->getId() !== $user->getId()) {
            return new RedirectResponse($urlGenerator->generate('homepage'));
        }

        // On crée le formulaire de paiement
        $payment = new Payment();
        $form = $this->createForm(PaymentFormType::class, $payment);
        $form->handleRequest($request);

        // Les options pour charger un moyen de paiement sauvegardé sont dans le template Twig

        if ($form->isSubmitted() && $form->isValid()) {
            // Propriétés données par le formulaire :
            // $addressBilling;
            // $addressDelivery;
            // $cardNumber;
            // $cardType;
            // $crypto;
            // $dateExpiration;
            // $fullName;

            // Propriétés relationnelles
            // $booking;
            // $savedPayment; (doit rester null pour l'historique de paiement)
            // (On proposera de sauvegarder le moyen de paiement dans la page récapitulatif)

            // Lien avec le Booking
            $payment->setBooking($booking);

            // on calcul le montant ici
            // travel price * places * (100 - promo) / 100
            $unit_price = $booking->getTravelDate()->getTravel()->getPrice();
            $promotionValue = 0;
            $promotion = $booking->getTravelDate()->getTravel()->getPromotion();
            if ($promotion !== null) {
                $promotionValue = $promotion->getAmount();
            }
            $total_price = $unit_price * $booking->getNumberPlaces() * (100 - $promotionValue) / 100;

            // on considère que le paiement est accepté
            // on met donc à jour le Booking
            $booking->setPaidAmount($total_price);
            $booking->setPromotionValue($promotionValue);
            $booking->setValidated(true);
            $booking->setPayment($payment);
            $currentDate = new DateTime();
            $booking->setDate($currentDate);

            // Doctrine met à jour la BD
            $entityManager->persist($payment);
            $entityManager->persist($booking);
            $entityManager->flush();

            // Redirection vers le récapitulatif
            return new RedirectResponse($urlGenerator->generate('recap', ['id' => $booking->getId()]));
        }


        return $this->render('booking/payment.html.twig', [
            'booking' => $booking,
            'paymentForm' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/recap/{id}", name="recap")
     */
    public function recap(
        Booking $booking,
        Security $security,
        UserRepository $userRepository,
        PaymentRepository $paymentRepository,
        SavedPaymentRepository $savedPaymentRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ) {
        // on vérifie si les détails du paiement sont déjà enregistrés
        // (situations : l'utilisateur a chargé un SavedPayment, 
        // ou bien l'utilisateur a rempli le formulaire en oubliant qu'il avait un SavedPayment correspondant)

        // On récupère l'utilisateur connecté
        // D'abord l'objet user security
        $connectedUser = $security->getUser();
        $email = $connectedUser->getUsername();
        // de ces infos on récupère l'entité User correspondante
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Unknown User.');
        }

        // requête pour trouver un SavedPayment correspondant à un Payment identique
        // null si rien n'est trouvé
        $savedPayments = $paymentRepository->findIdenticalPaymentFromUser($booking->getPayment(), $user);
        if ($savedPayments === []) {
            $savedPayment = null;
        } else {
            $savedPayment = $savedPayments[0];
        }

        // Sauvegarder le paiement
        // vérifier que le nom n'est pas déjà utilisé et n'est pas "New"
        $paymentNameError = '';
        $isPaymentRegistered = false;
        if ($request->request->has('savedPaymentName')) {
            $name = $request->request->get('savedPaymentName');

            $existingName = $savedPaymentRepository->findBy(['name' => $name]);

            if ($name === "New" || $existingName) {
                // Erreur avec le nom
                $paymentNameError = 'The name cannot be "New" or an already registered name.';
            } else {
                // Nouveau Payment : on copie les infos du paiement dans Booking (mais ni l'id ni booking)
                $newPayment = new Payment();
                $paymentToCopy = $booking->getPayment();
                $newPayment->setAddressBilling($paymentToCopy->getAddressBilling());
                $newPayment->setAddressDelivery($paymentToCopy->getAddressDelivery());
                $newPayment->setCardNumber($paymentToCopy->getCardNumber());
                $newPayment->setCardType($paymentToCopy->getCardType());
                $newPayment->setCrypto($paymentToCopy->getCrypto());
                $newPayment->setDateExpiration($paymentToCopy->getDateExpiration());
                $newPayment->setFullName($paymentToCopy->getFullName());

                // Enregistrement du SavedPayment
                $newSavedPayment = new SavedPayment();
                $newSavedPayment->setName($name);
                $newSavedPayment->setUser($user);
                $newSavedPayment->setPayment($newPayment);

                $entityManager->persist($newPayment);
                $entityManager->persist($newSavedPayment);
                $entityManager->flush();

                $isPaymentRegistered = true;
            }
        }

        return $this->render('booking/recap.html.twig', [
            'booking' => $booking,
            'existingPayment' => $savedPayment,
            'paymentNameError' => $paymentNameError,
            'isPaymentRegistered' => $isPaymentRegistered,
        ]);
    }
}