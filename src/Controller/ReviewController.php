<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Travel;
use App\Form\ReviewFormType;
use App\Repository\ReviewRepository;
use App\Repository\TravelDateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review/{id}", name="review")
     */
    public function review(Review $review)
    {
        return $this->render('review/review.html.twig', [
            'review' => $review,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/write_review/{id}", name="write_review")
     */
    public function write_review(
        Travel $travel,
        Request $request,
        Security $security,
        UserRepository $userRepository,
        TravelDateRepository $travelDateRepository,
        ReviewRepository $reviewRepository,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ) {
        // Vérification que User a bien effectué le voyage :
        // Existence d'une TravelDate associée dont dateReturn est antérieure à aujourd'hui
        $connectedUser = $security->getUser();
        $email = $connectedUser->getUsername();
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Unknown User.');
        }

        $isAllowedToReview = $travelDateRepository->findPastTravels($travel, $user);

        if ($isAllowedToReview) {
            // Création du formulaire
            // S'il existe déjà une Review de ce Travel, c'est une modification
            // Pré-remplir le formulaire
            $review = $reviewRepository->findOneBy(['travel' => $travel, 'user' => $user]) ?? new Review();

            $form = $this->createForm(ReviewFormType::class, $review);
            $form->handleRequest($request);

            // Traitement du formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                // Par le formulaire on a $note et $comment
                // on ajoute $user et $travel au cas où c'est une nouvelle Review
                $review->setTravel($travel);
                $review->setUser($user);

                $entityManager->persist($review);
                $entityManager->flush();
            }
        } else {
            // Redirection vers la page du Travel
            return new RedirectResponse($urlGenerator->generate('travel', ['id' => $travel]));
        }

        return $this->render('review/write_review.html.twig', [
            'travel' => $travel,
            'reviewForm' => $form->createView(),
        ]);
    }
}