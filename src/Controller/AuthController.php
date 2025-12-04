<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/auth', name: 'app_auth')]
    public function auth(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        AuthenticationUtils $authenticationUtils
    ): Response
    {
        // --- Formulaire d'inscription ---
        $user = new User();
        $registerForm = $this->createForm(RegistrationType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $email = trim($user->getEmail());
            if ($userRepository->findOneBy(['email' => $email])) {
                $this->addFlash('error', 'Cet utilisateur existe déjà.');
            } else {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $registerForm->get('plainPassword')->getData())
                );
                $user->setRoles(['ROLE_USER']);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->addFlash('success', 'Inscription réussie !');
                return $this->redirectToRoute('app_user_profile');
            }
        }

        // --- Formulaire de connexion ---
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // **CHEMIN MODIFIÉ**
        return $this->render('pages/registration/auth.html.twig', [
            'registerForm' => $registerForm->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
