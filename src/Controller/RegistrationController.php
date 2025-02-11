<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Service\EmailService; // Service d'email avec Mailtrap
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends AbstractController
{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
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
                $entityManager->persist($user);
                $entityManager->flush();

                // 📩 **Envoi de l'email via Mailtrap**
                $this->emailService->sendConfirmationEmail(
                    $user->getEmail(),
                    $user->getUseridentifier(),
                    'http://mon-site.com/confirmation?token=abc123' // URL factice
                );

                $this->addFlash('success', 'Inscription réussie ! Un email de confirmation vous a été envoyé.');
             
            } else {
                $this->addFlash('error', 'Le mot de passe est requis.');
            }
        }

        return $this->render('pages/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
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
