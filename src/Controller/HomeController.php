<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        // Si on se connecte en tant qu'Admin sur la homepage, on est renvoyé vers le backoffice
        if ($security->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($urlGenerator->generate('backoffice_index'));
        }
        return $this->render('index.html.twig');
    }
}
