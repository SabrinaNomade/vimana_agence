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

class RegistrationController extends AbstractController
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
        $form = $this->createForm(RegistrationType::class, $user);
    
        // Gère la requête HTTP et lie les données au formulaire d'inscription
        $form->handleRequest($request);
    
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Nettoyer l'email avant de vérifier s'il existe
            $cleanEmail = trim($user->getEmail());
            $user->setEmail($cleanEmail);
    
            // Vérification si l'email existe déjà
            $userExistant = $userRepository->findOneBy(['email' => $cleanEmail]);
    
            // Récuperer l'utilisateur de la base données en utilsant le mail fournit par l'utlisateur
            $userExistant = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($userExistant) {

                // Ajouter un message flash et rediriger vers la page utilisateur
                $this->addFlash('error', 'Cet utilisateur existe déjà.');
                return $this->render('pages/registration/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
    
           
            
            // Récupérer le mot de passe en clair soumis par l'utilisateur
            $plainPassword = $form->get('plainPassword')->getData();
    
            // Vérifier si le champ plainPassword n'est pas null
            if ($plainPassword) {
                // Encode le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
    
                // Sauvegarde l'utilisateur en base de données
                $entityManager->persist($user);
                $entityManager->flush();
    
                // Ajoute un message de succès
                $this->addFlash('success', 'Inscription réussie !');
    
                // Redirige vers la page de connexion
                return $this->redirectToRoute('app_connexion');
            } else {
                $this->addFlash('error', 'Le mot de passe est requis.');
            }
        }
    
        // Si la méthode est GET ou si le formulaire n'est pas valide, on rend la vue
        return $this->render('pages/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/connexion', name: 'app_connexion')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    // Vérifiez si l'utilisateur est déjà connecté
    if ($this->getUser()) {
        $this->addFlash('success',' vous etes deja connecté.');
        
    }
    
    // Obtenez l'erreur d'authentification, s'il y en a
    $error = $authenticationUtils->getLastAuthenticationError();

    // Obtenez le dernier nom d'utilisateur saisi
    $lastUsername = $authenticationUtils->getLastUsername();

    // Renvoyez la vue avec les erreurs et le dernier nom d'utilisateur saisi
    return $this->render('pages/registration/login.html.twig', [
        'last_username' => $lastUsername, // Utilise le bon nom de variable
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
