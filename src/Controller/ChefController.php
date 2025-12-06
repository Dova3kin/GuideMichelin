<?php

namespace App\Controller;

use App\Entity\Chef;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChefController extends AbstractController
{

    public function chefList(EntityManagerInterface $eM)
    {
        $chefs = $eM->getRepository(Chef::class)->findAll();
        return $this->render("chef/listChef.html.twig", ["chefs" => $chefs]);
    }

    public function voirChef(EntityManagerInterface $eM, $id)
    {
        $chef = $eM->getRepository(Chef::class)->find($id);
        return $this->render("chef/voir.html.twig", ["chef" => $chef]);
    }

    public function deleteChef(EntityManagerInterface $eM, $id)
    {
        $chef = $eM->getRepository(Chef::class)->find($id);
        $eM->remove($chef);
        $eM->flush();
        return $this->redirectToRoute("guide_michelin_list_chef");
    }
}
