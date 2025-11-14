<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuideMichelinController extends AbstractController
{
    public function accueil($nom)
    {
        return $this->render('accueil.html.twig', ['nom' => $nom]);
    }

    public function menu()
    {
        return $this->render("menu.html.twig");
    }
}
