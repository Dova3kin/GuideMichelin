<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChefController extends AbstractController
{
    #[Route('/chef', name: 'app_chef')]
    public function index(): Response
    {
        return $this->render('chef/index.html.twig', [
            'controller_name' => 'ChefController',
        ]);
    }

    public function modifier(EntityManagerInterface $eM, $nom, $prenom) {}
}
