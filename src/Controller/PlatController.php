<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Plat;
use App\Form\Type\PlatType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

final class PlatController extends AbstractController
{
    #[Route('/plat', name: 'app_plat')]
    public function index(): Response
    {
        return $this->render('plat/index.html.twig', [
            'controller_name' => 'PlatController',
        ]);
    }

    public function add(EntityManagerInterface $eM, Request $r)
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat, ["action" => $this->generateUrl("plat_ajouter")]);
        $form->add('submit', SubmitType::class, ['label' => "ajouter"]);
        $form->handleRequest($r);
        if ($form->isSubmitted()) {
            $eM->persist($plat);
            $eM->flush();
            return $this->redirectToRoute("guide_michelin_accueil");
        }
        return $this->render("plat/add.html.twig", ['Form' => $form->createView()]);
    }
}
