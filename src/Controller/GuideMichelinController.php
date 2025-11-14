<?php

namespace App\Controller;

use App\Entity\Resto;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

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

    public function voir(EntityManagerInterface $entityManager, $id)
    {
        $resto = $entityManager->getRepository(Resto::class)->find($id);
        if (!$resto)
            throw $this->createNotFoundException("Resto[id=" . $id . "n'existe pas");
        return $this->render("voir.html.twig", ['resto' => $resto]);
    }

    public function add(EntityManagerInterface $entityManager, $nom, $chef, $etoile)
    {
        $resto = new Resto;
        $resto->setNom($nom);
        $resto->setChef($chef);
        $resto->setNbEtoile($etoile);
        $entityManager->persist($resto);
        $entityManager->flush();
        return $this->redirectToRoute("guide_michelin_voir", ['id' => $resto->getId()]);
    }
}
