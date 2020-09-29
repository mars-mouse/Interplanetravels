<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController
{
    /**
     * @Route("/promotions", name="promotions")
     */
    public function promotions(PromotionRepository $promotionRepository)
    {
        $promotions = $promotionRepository->findActivePromotions();

        return $this->render('promotion/promotions.html.twig', [
            'promotions_list' => $promotions,
        ]);
    }

    /**
     * @Route("/promotion/{id}", name="promotion")
     */
    public function promotion(Promotion $promotion, TravelRepository $travelRepository)
    {
        $travels = $travelRepository->findBy(['promotion' => $promotion->getId()]);

        return $this->render('promotion/promotion.html.twig', [
            'promotion' => $promotion,
            'travels_list' => $travels,
        ]);
    }
}