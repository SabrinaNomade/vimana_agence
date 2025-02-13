<?php

namespace App\Controller\Admin;

use App\Entity\Email;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EmailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Email::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'recipient', // Email du destinataire
            'subject',   // Sujet de l'email
            'sentAt',    // Date d'envoi
            'content',   // Contenu de l'email
        ];
    }
}
