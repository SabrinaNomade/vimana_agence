<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPagesController extends AbstractController
{

    #[Route('/mentions-legales', name: 'mentions_legales')]
public function mentionsLegales(): Response
{
    return $this->render('static/mentions_legales.html.twig');
}

#[Route('/politique-confidentialite', name: 'politique_confidentialite')]
public function politiqueConfidentialite(): Response
{
    return $this->render('static/politique_confidentialite.html.twig');
}

#[Route('/cgu', name: 'cgu')]
public function cgu(): Response
{
    return $this->render('static/cgu.html.twig');
}

#[Route('/newsletter', name: 'newsletter')]
public function newsletter(): Response
{
    return $this->render('static/newsletter.html.twig');
}

}
