<?php
// src/Controller/ExpertiseController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpertiseController extends AbstractController
{
    #[Route('/expertise', name: 'app_expertise')]  
public function index(): Response
{
    return $this->render('pages/expertise/index.html.twig');
}


    /**
     * @Route("/expertise/{id}", name="expertise_show")
     */
   // public function show(int $id): Response
    //{
        // Exemple : Vous pourriez récupérer des informations d'expertise depuis la base de données avec Doctrine
        // Par exemple, un service ou un repository pour charger une expertise par son ID.
        
        // Pour l'instant, nous allons juste afficher l'ID de l'expertise
       // return $this->render('expertise/show.html.twig', [
            //'id' => $id,
       // ]);
    }

