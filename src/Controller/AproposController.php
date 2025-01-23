<?php
// src/Controller/AproposController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AproposController extends AbstractController
{
    #[Route('/apropos', name: 'app_apropos')] // Nom de la route ajusté
    public function index(): Response
    {
        return $this->render('pages/apropos/index.html.twig'); // Vérifie le chemin du fichier Twig
    }
}
