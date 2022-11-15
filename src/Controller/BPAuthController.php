<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BPAuthController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('auth/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout() :Response
    {
        throw new \Exception('logout() should never be reached');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request,ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher) :Response
    {
        if($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();
            $user = new User();

            $email = $request->get('mail');
            $username = $request->get('username');
            $password = $request->get('password');

            $hashedPassword = $passwordHasher->hashPassword($user,$password);

            $user->setEmail($email);
            $user->setPassword($hashedPassword);
            $user->setUsername($username);


            $entityManager->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->render('auth/login.html.twig', [
                'controller_name' => 'AuthController',
            ]);
        }
        return $this->render('auth/register.html.twig',[
            'controller_name' => 'AuthController',
        ]);
    }
}
