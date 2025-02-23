<?php
// src/Controller/ContactController.php

namespace App\Controller;

use App\Form\ContactType;
use App\Document\Contact;
use App\Service\BrevoMailer; // Assure-toi que le service BrevoMailer est importé
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    private BrevoMailer $brevoMailer; // Déclare la variable pour le service BrevoMailer

    // Injection du service BrevoMailer via le constructeur
    // Le service BrevoMailer est utilisé pour envoyer des emails via Brevo (anciennement Sendinblue)
    public function __construct(BrevoMailer $brevoMailer)
    {
        $this->brevoMailer = $brevoMailer;  // Initialisation du service BrevoMailer
    }

    // Route pour afficher et traiter le formulaire de contact
    #[Route('/contact', name: 'app_contact')] 
    public function contact(Request $request): Response
    {
        // Création d'un nouvel objet Contact
        $contact = new Contact();

        // Création du formulaire basé sur la classe ContactType
        $form = $this->createForm(ContactType::class, $contact);

        // Traitement de la requête HTTP pour le formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données du formulaire, comme le nom, l'email et le message
            $nom = $contact->getName();
            $email = $contact->getEmail();
            $message = $contact->getMessage();

            // Appel de la méthode pour envoyer l'email à l'administrateur via BrevoMailer
            // Cette méthode utilise le service BrevoMailer pour envoyer l'email
            $this->brevoMailer->sendContactEmail($nom, $email, $message);

            // Ajoute un message flash de succès pour informer l'utilisateur que son message a été envoyé
            $this->addFlash('success', 'Message envoyé avec succès !');

            // Redirige l'utilisateur vers la même page après une soumission réussie
            return $this->redirectToRoute('app_contact');
        }

        // Rend la vue 'contact.html.twig' et passe le formulaire à la vue
        return $this->render('pages/user/contact.html.twig', [
            'form' => $form->createView(), // Création du formulaire à afficher dans la vue
        ]);
    }
}


