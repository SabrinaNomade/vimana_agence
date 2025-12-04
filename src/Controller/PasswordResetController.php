<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BrevoMailer;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class PasswordResetController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;
    private BrevoMailer $brevoMailer;

    // Injection des services 
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        BrevoMailer $brevoMailer
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->brevoMailer = $brevoMailer;
    }

    // Route pour envoyer l'email de réinitialisation du mdp (route pour envoyer l'email pour envoyer)
    #[Route('/forgot_password', name: 'app_forgotpassword', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) { 
                // Génère un token de réinitialisation unique() a utiliser q'unen seule fois )

                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $this->entityManager->flush(); // Sauvegarde le token dans la base de données

                // Génère l'URL pour réinitialiser le mot de passe
                $resetUrl = $this->generateUrl('app_resetpassword', ['token' => $token], 0); // URL sans le domaine

                // Envoie l'email de réinitialisation via le service BrevoMailer (modifier l'api recuperer)
                $response = $this->brevoMailer->sendPasswordResetEmail($email, $resetUrl);

                if (isset($response['error'])) {
                    $this->addFlash('error', $response['error']);
                } else {
                    $this->addFlash('success', 'Un email de réinitialisation a été envoyé.');
                }

                return $this->redirectToRoute('app_home'); // Redirection après l'envoi
            }

            $this->addFlash('error', 'Email introuvable!');
        }

        return $this->render('email/forgot_password.html.twig');
    }

    // Route pour réinitialiser le mot de passe
    #[Route('/reset-password/{token}', name: 'app_resetpassword', methods: ['GET', 'POST'])]
    public function resetPassword(Request $request, UserRepository $userRepository, string $token): Response
    {
        // Recherche de l'utilisateur par le token
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Ce lien est invalide ou a expiré.');
            return $this->redirectToRoute('app_forgotpassword');
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->get('newPassword'); // Récupère le nouveau mot de passe

            if ($newPassword) {
                // Hashage du mot de passe
                $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);

                $user->setPassword($hashedPassword);
                $user->setResetToken(null); // Supprime le token après la réinitialisation
                $this->entityManager->flush(); // Sauvegarde dans la base de données

                $this->addFlash('success', 'Votre mot de passe a été réinitialisé.');
                return $this->redirectToRoute('app_connexion'); // Redirige vers la page de connexion
            }
        }

        // Afficher le formulaire de réinitialisation
        return $this->render('email/password_reset.html.twig', [
            'token' => $token, // Le token est bien passé à la vue ici
        ]);
    }
}
