<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Importation des annotations (pas nécessaire avec les attributs)

// Vous pouvez remplacer cette annotation par l'attribut PHP
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]  // Attribut PHP pour la route
    public function index(): Response
    {
        return $this->render('home/index.html.twig');  // Utilisation de la bonne structure de chemin
    }
}
