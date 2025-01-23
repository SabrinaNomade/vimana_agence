<?php
// src/Controller/AuthController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
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
    // Route pour l'inscription
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User(); // Crée un nouvel utilisateur

        // Crée le formulaire d'inscription basé sur la classe RegisterType
        $form = $this->createForm(RegisterType::class, $user);

        // Gère la requête HTTP et lie les données au formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            $userExistant = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if ($userExistant) {
                // Ajouter un message flash et rediriger
                $this->addFlash('error', 'Cet utilisateur existe déjà.');
                return $this->redirectToRoute('app_register');
            }
            // Encode le mot de passe en utilisant UserPasswordHasherInterface
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Sauvegarde l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoute un message de succès
            $this->addFlash('success', 'Inscription réussie !');

            // Redirige vers la page d'accueil ou la page de connexion
            return $this->redirectToRoute('app_home');
        }

        // Si la méthode est GET ou si le formulaire n'est pas valide, on rend la vue
        return $this->render('pages/auth/register.html.twig', [
            'form' => $form->createView(), // Passe la vue du formulaire au template
        ]);

    }

    #[Route('/connexion', name: 'app_connexion')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    // Vérifiez si l'utilisateur est déjà connecté
    if ($this->getUser()) {
        return $this->redirectToRoute('app_home');
    }

    // Obtenez l'erreur d'authentification, s'il y en a
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    // Renvoyez la vue avec les erreurs et le dernier nom d'utilisateur saisi
    return $this->render('pages/auth/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
    ]);
}


    // Route pour la déconnexion (facultatif, géré automatiquement par Symfony)
    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function logout(): void
    {
        // Symfony gère déjà la déconnexion, cette méthode peut être vide
    }

}