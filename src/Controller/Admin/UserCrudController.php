<?php
namespace App\Controller\Admin;

// Importation des classes nécessaires pour la gestion de l'entité User et l'interface EasyAdmin
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

// Définition de la classe UserCrudController qui hérite de AbstractCrudController
// Cette classe permet de gérer l'interface d'administration pour l'entité User
class UserCrudController extends AbstractCrudController
{
    // Cette méthode renvoie la classe de l'entité User pour qu'EasyAdmin sache de quelle entité il s'agit
    public static function getEntityFqcn(): string
    {
        // On retourne ici la classe de l'entité User
        return User::class;
    }

    // Cette méthode permet de définir les champs à afficher sur les pages de l'interface d'administration
    public function configureFields(string $pageName): iterable
    {
        return [
            // Le champ 'id' est affiché mais sera caché dans le formulaire d'édition
            IdField::new('id')->hideOnForm(),

            // Le champ 'email' est un champ texte pour l'email de l'utilisateur
            TextField::new('email'),

            // Le champ 'isActive' est un champ booléen permettant d'activer ou désactiver le compte de l'utilisateur
            BooleanField::new('isActive', 'Compte Actif'), // 'Compte Actif' est l'étiquette affichée

            // Le champ 'roles' est un champ tableau qui affiche les rôles de l'utilisateur
            ArrayField::new('roles'),

            // Le champ 'description' est un éditeur de texte riche pour une description de l'utilisateur
            TextEditorField::new('description'),
        ];
    }

    // Cette méthode permet de configurer les actions disponibles dans l'interface d'administration
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Ajoute une action de détail sur la page d'index (liste des utilisateurs)
            ->add(Crud::PAGE_INDEX, Action::DETAIL) 

            // Modifie l'action de suppression sur la page d'index, on peut personnaliser cette action si nécessaire
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                // Ici, tu pourrais ajouter des conditions ou personnaliser l'action
                return $action; // On retourne l'action après modification
            });
    }
}
