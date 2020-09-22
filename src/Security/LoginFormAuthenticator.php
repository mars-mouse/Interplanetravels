<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return $request->isMethod('POST')
            /*verification que POST a des champs loginEmail et loginPassword*/
            && $request->request->has('loginEmail')
            && $request->request->has('loginPassword');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('loginEmail'),
            'password' => $request->request->get('loginPassword'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // Si on a la route de retour dans la Session, on utilise ça
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // Récuperation du nom de la page dans les paramètres de route
        $route = $request->attributes->get('_route');
        // Si on vient de la page de login, on est redirigé vers la homepage
        if ($route === self::LOGIN_ROUTE) {
            return new RedirectResponse($this->urlGenerator->generate('homepage'));
        }

        // Récuperation des paramètres de la route
        $params = $request->attributes->get('_route_params');
        // Ajout paramètres GET s'il y en avait (avec query all pour avoir tous les params get)
        $params = array_merge($request->query->all(), $params);
        // Recrée la route avec tous les paramètres
        return new RedirectResponse($this->urlGenerator->generate($route, $params));
    }

    // Si login fail : on reste sur la meme page
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // On enregistre l'exception en Session
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        // Récuperation du nom de la page dans les paramètres de route
        $route = $request->attributes->get('_route');
        // Récuperation des paramètres de la route
        $params = $request->attributes->get('_route_params');
        // Ajout paramètres GET s'il y en avait (avec query all pour avoir tous les params get)
        $params = array_merge($request->query->all(), $params);
        // Recrée la route avec tous les paramètres
        return new RedirectResponse($this->urlGenerator->generate($route, $params));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
