<?php

namespace App\Controller;

use App\Entity\Resto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\Type\RestoType;

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

    public function add2(EntityManagerInterface $entityManager, Request $request)
    {
        $resto = new Resto;
        $form = $this->createFormBuilder($resto)
            ->add('nom', TextType::class)
            ->add('chef', TextType::class)
            ->add('nbEtoile', IntegerType::class)
            ->add('envoyer', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($resto);
            $entityManager->flush();
            return $this->redirectToRoute("guide_michelin_ajouter2", ["id" => $resto->getId()]);
        }
        return $this->render(
            'ajouter2.html.twig',
            array('monFormulaire' => $form->createView())
        );
    }

    public function list(EntityManagerInterface $entityManager)
    {
        $restos = $entityManager
            ->getRepository(Resto::class)->findAll();
        return $this->render('list.html.twig', array('restos' => $restos));
    }

    public function modifier(EntityManagerInterface $entityManager, $id)
    {
        $resto = $entityManager->getRepository(Resto::class)->find($id);
        if (!$resto)
            throw $this->createNotFoundException('Resto[id=' . $id . '] inexistante');
        $form = $this->createForm(
            RestoType::class,
            $resto,
            ['action' => $this->generateUrl(
                'guide_michelin_modifier_suite',
                array('id' => $resto->getId())
            )]
        );
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render(
            'modifier.html.twig',
            array('monFormulaire' => $form->createView())
        );
    }

    public function modifierSuite(EntityManagerInterface $entityManager, Request
    $request, $id)
    {
        $resto = $entityManager->getRepository(Resto::class)->find($id);

        if (!$resto)
            throw $this->createNotFoundException('Resto[id=' . $id . '] inexistante');
        $form = $this->createForm(
            RestoType::class,
            $resto,
            ['action' => $this->generateUrl(
                'guide_michelin_modifier_suite',
                array('id' => $resto->getId())
            )]
        );
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($resto);
            $entityManager->flush();
            return $this->redirectToRoute('guide_michelin_voir', ['id' => $resto->getId()]);
        }
        return $this->render(
            'resto/modifier.html.twig',
            array('monFormulaire' => $form->createView())
        );
    }
}
