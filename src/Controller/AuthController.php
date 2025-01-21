<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register_page')]
    public function index(Request $req, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Logique d'inscription
        $user = new User();
        $inscriptionForm = $this->createForm(RegisterType::class, $user);
        $inscriptionForm->handleRequest($req);

        if ($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()) {
            $userExistant = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($userExistant) {
                $this->addFlash('error', 'Utilisateur déjà inscrit !');
                return $this->redirectToRoute('app_register_page');
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $userRepository->save($user, true);

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('app_login');
        }

        // Formulaire de connexion
        $connexionForm = $this->createForm(LoginType::class);
        return $this->render('pages/auth/register.html.twig', [
            'inscriptionForm' => $inscriptionForm->createView(),
            'connexionForm' => $connexionForm->createView(),
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function connexionTraitement(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Récupérer l'erreur et l'email du dernier essai
        $error = $authenticationUtils->getLastAuthenticationError();
        $dernierEmail = $authenticationUtils->getLastUsername();

        // Gérer la redirection après une connexion réussie
        $targetPath = $request->get('target_path', $this->generateUrl('homepage')); // Par défaut redirige vers la page d'accueil

        return $this->render('pages/auth/login.html.twig', [
            'connexion' => [
                'error' => $error ? $error->getMessageKey() : null,
                'dernierEmail' => $dernierEmail,
                'target_path' => $targetPath
            ]
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide car Symfony gère la déconnexion automatiquement
    }
}
