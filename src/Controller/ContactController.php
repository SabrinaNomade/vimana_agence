<?php
// src/Controller/ContactController.php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();

        // Créer le formulaire
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les données en base (optionnel)
            $entityManager->persist($contact);
            $entityManager->flush();

            // Ajouter un message flash pour l'utilisateur
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            // Rediriger après soumission
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
