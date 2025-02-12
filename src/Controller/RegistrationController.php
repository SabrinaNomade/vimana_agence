<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Service\BrevoMailer; // Service d'email avec Brevo
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class RegistrationController extends AbstractController
{
    private $brevoMailer;
    private $entityManager;

    public function __construct(BrevoMailer $brevoMailer, EntityManagerInterface $entityManager)
    {
        $this->brevoMailer = $brevoMailer;
        $this->entityManager = $entityManager; // Stocke l'EntityManager
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si l'utilisateur existe déjà
            $cleanEmail = trim($user->getEmail());
            $user->setEmail($cleanEmail);
            $userExistant = $userRepository->findOneBy(['email' => $cleanEmail]);

            if ($userExistant) {
                $this->addFlash('error', 'Cet utilisateur existe déjà.');
                return $this->render('pages/registration/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Hachage du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);

                // Création d'un token de confirmation
                $confirmationToken = bin2hex(random_bytes(16)); // Token unique
                $user->setConfirmationToken($confirmationToken);

                $user->setRoles(['ROLE_USER']); // Assigner le rôle ROLE_USER
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // Envoi de l'email de confirmation avec BrevoMailer
                try {
                    $confirmationUrl = $this->generateUrl(
                        'app_confirm_email',
                        ['token' => $confirmationToken],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );

                    // Appel de la méthode d'envoi d'email
                    $responseData = $this->brevoMailer->sendEmail(
                        $user->getEmail(),
                        'Confirmez votre email',
                        'Cliquez sur ce lien pour confirmer votre email: ' . $confirmationUrl
                    );

                    // Log ou affichage pour voir la réponse de l'API Brevo
                    var_dump($responseData); // Tu peux retirer cette ligne une fois testé

                    $this->addFlash('success', 'Inscription réussie ! Un email de confirmation vous a été envoyé.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email de confirmation.');
                }
            } else {
                $this->addFlash('error', 'Le mot de passe est requis.');
            }
        }

        return $this->render('pages/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Déplace la méthode confirmEmail en dehors de la méthode register
    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token, UserRepository $userRepository): Response
    {
        // Rechercher l'utilisateur par le token de confirmation
        $user = $userRepository->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            // Si aucun utilisateur trouvé, afficher la page d'erreur
            return $this->render('email/confirmation_error.html.twig');
        }
        
        // Activer l'utilisateur
        $user->setIsActive(true);  // Activer l'utilisateur

        // Supprimer le token de confirmation
        $user->setConfirmationToken(null); // Supprimer le token

        // Sauvegarder les modifications dans la base de données
        $this->entityManager->flush(); // Utilise l'EntityManager injecté

        // Afficher la page de confirmation réussie
        return $this->render('email/confirmation_success.html.twig');
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('success', 'Vous êtes déjà connecté.');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/registration/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function logout(): void
    {
        // Symfony gère déjà la déconnexion, cette méthode peut être vide
    }
}
