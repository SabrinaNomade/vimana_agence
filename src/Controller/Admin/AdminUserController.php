<?php
// Définition du namespace et du contrôleur d'administration
// Ce contrôleur gère les actions liées aux utilisateurs dans l'interface admin
namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]  //  base de la route pour toutes les actions de ce contrôleur admin
class AdminUserController extends AbstractController
{
    // Route pour afficher la liste des utilisateurs
    #[Route('/users', name: 'users_list')] 
    public function usersList(UserRepository $userRepository): Response
    {
        // Récupère tous les utilisateurs via le repository
        $users = $userRepository->findAll();

        // Rendu du template Twig avec la liste des utilisateurs à afficher
        return $this->render('admin/users.html.twig', [
            'users' => $users,  // On passe les utilisateurs récupérés à la vue
        ]);
    }

    // Route pour activer ou désactiver un utilisateur
    #[Route('/user/toggle/{id}', name: 'admin_toggle_user')]
    public function toggleUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Recherche l'utilisateur en fonction de l'ID passé dans l'URL
        $user = $userRepository->find($id);


























        
    

        // Si l'utilisateur n'est pas trouvé, on affiche un message d'erreur
        if (!$user) {
            $this->addFlash('danger', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('admin_users_list');
        }

        // On inverse le statut actif/inactif de l'utilisateur
        $user->setIsActive(!$user->isActive());
        
        // On persiste les changements dans la base de données
        $entityManager->flush();

        // Affichage d'un message de succès et redirection vers la liste des utilisateurs
        $this->addFlash('success', 'Statut de l’utilisateur mis à jour.');
        return $this->redirectToRoute('admin_users_list');
    }

    // Route pour supprimer un utilisateur
    #[Route('/user/delete/{id}', name: 'admin_delete_user', methods: ['POST'])]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Recherche de l'utilisateur à supprimer via l'ID
        $user = $userRepository->find($id);

        // Si l'utilisateur n'est pas trouvé, on affiche un message d'erreur
        if (!$user) {
            $this->addFlash('danger', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('admin_users_list');
        }

        // On supprime l'utilisateur de la base de données
        $entityManager->remove($user);
        $entityManager->flush();

        // Affichage d'un message de succès et redirection vers la liste des utilisateurs
        $this->addFlash('success', 'Utilisateur supprimé.');
        return $this->redirectToRoute('admin_users_list');
    }
}


