<?php

// src/Controller/ProfileController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'app_user_profile')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        if (!$user) {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
            return $this->redirectToRoute('app_connexion');
        }

        // Créer le formulaire basé sur l'entité User
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les changements dans la base de données
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            // Redirection vers la page d'accueil après la mise à jour
            return $this->redirectToRoute('app_home');
        }

        // Rendu de la vue de profil avec le formulaire
        return $this->render('admin/user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

