<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/auth', name: 'auth_')]
class SecurityController extends AbstractController
{

    private UserRepository $userRepository;
    #[Route(path: '/login', name: 'login', methods: ['POST', 'GET'])]
    public function login(AuthenticationUtils $authenticationUtils, Security $security, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = new User();
        $formUser = $this->createForm(RegistrationFormType::class, $user);
        $formUser->handleRequest($request);
        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'formUser' => $formUser,
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(Security $security): Response
    {
//      throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
      return $security->logout(false);
    }
}
