<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppLoginExtension extends AbstractExtension
{
    private $authenticationUtils;
    private $authenticationError = null;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            // new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            /*indique a twig les fonction à executer*/
            new TwigFunction('login_error', [$this, 'loginError']),
            new TwigFunction('login_username', [$this, 'loginUsername']),
        ];
    }

    public function loginError()
    {
        // On enregistre l'erreur dans une propriété car on va utiliser la fonction
        // plusieurs fois sur le même template Twig
        // ça évite de faire getLastAuthenticationError() à chaque fois
        if ($this->authenticationError === null) {
            $this->authenticationError = $this->authenticationUtils->getLastAuthenticationError();
        }
        // On retourne l'erreur enregistrée
        return $this->authenticationError;
    }

    public function loginUsername()
    {
        // Récuperation du dernier login dans la Session pour pré-remplir le champ de login
        return $this->authenticationUtils->getLastUsername();
    }
}
