<?php

namespace App\Controller\Admin;

// Importation de l'entité Email et du contrôleur EasyAdmin nécessaire pour gérer l'entité dans l'interface d'administration
use App\Entity\Email;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EmailCrudController extends AbstractCrudController
{
    // Cette méthode permet de retourner la classe de l'entité que nous allons gérer dans l'interface d'administration
    public static function getEntityFqcn(): string
    {
        // Retourne la classe Email, ce qui signifie qu'on va gérer cette entité dans EasyAdmin
        return Email::class;
    }

    // Cette méthode définit les champs à afficher dans l'interface d'administration pour l'entité Email
    public function configureFields(string $pageName): iterable
    {
        return [
            // Champ 'recipient' pour afficher l'email du destinataire
            'recipient', // Email du destinataire
            
            // Champ 'subject' pour afficher le sujet de l'email
            'subject',   // Sujet de l'email

            // Champ 'sentAt' pour afficher la date et l'heure d'envoi de l'email
            'sentAt',    // Date d'envoi

            // Champ 'content' pour afficher le contenu de l'email
            'content',   // Contenu de l'email
        ];
    }
}
