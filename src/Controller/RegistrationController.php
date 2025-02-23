<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RegistrationController extends AbstractController
{
    private $entityManager;
    private $tokenStorage;

    // Injection des services nécessaires via le constructeur
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Crée un nouvel objet utilisateur pour le formulaire
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);  // Traite les données soumises du formulaire

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Nettoie l'email pour éviter les espaces et vérifie s'il existe déjà
            $cleanEmail = trim($user->getEmail());
            $user->setEmail($cleanEmail);
            $userExistant = $userRepository->findOneBy(['email' => $cleanEmail]);

            // Si l'email existe déjà dans la base de données
            if ($userExistant) {
                // Affiche un message d'erreur si l'email existe
                $this->addFlash('error', 'Cet utilisateur existe déjà.');
                return $this->render('pages/registration/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Si le mot de passe est renseigné, on le hache pour le stocker
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);  // Hache le mot de passe
                $user->setPassword($hashedPassword);  // Assigne le mot de passe haché à l'utilisateur

                $user->setRoles(['ROLE_USER']);  // Donne à l'utilisateur le rôle de base
                $this->entityManager->persist($user);  // Sauvegarde l'utilisateur dans la base
                $this->entityManager->flush();  // Applique les changements à la base de données

                // Message de succès après l'inscription
                $this->addFlash('success', 'Inscription réussie !');

                return $this->redirectToRoute('app_connexion');
            } else {
                // Si le mot de passe n'est pas renseigné
                $this->addFlash('error', 'Le mot de passe est requis.');
            }
        }

        // Retourne le formulaire d'inscription dans la vue
        return $this->render('pages/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route de connexion
    #[Route('/connexion', name: 'app_connexion')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers son profil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_profile', ['id' => $this->getUser()->getUserIdentifier()]);
        }

        // Récupère l'erreur de connexion et le dernier username
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Retourne la vue de connexion
        return $this->render('pages/registration/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    // Déconnexion de l'utilisateur
    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function logout(SessionInterface $session, TokenStorageInterface $tokenStorage): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        // Invalide la session et supprime le token de sécurité
        $session->invalidate();
        $tokenStorage->setToken(null);

        // Si le cookie de session existe, on l'expire
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Redirige l'utilisateur vers la page de connexion après déconnexion
        return $this->redirectToRoute('app_connexion');
    }
}

            