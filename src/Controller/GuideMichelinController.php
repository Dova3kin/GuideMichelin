<?php

namespace App\Controller;

use App\Entity\Resto;
use App\Entity\Chef;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
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

    public function add2(EntityManagerInterface $entityManager, Request $request, Session $session)
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
            $session->getFlashBag()->add("infoAdd", "Resto ajouté : " . $resto->getNom());
            return $this->redirectToRoute("guide_michelin_list", ["id" => $resto->getId()]);
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

    public function delete(EntityManagerInterface $eM, Session $session,  $id)
    {
        $resto = $eM->getRepository(Resto::class)->find($id);
        $eM->remove($resto);
        $eM->flush();
        $session->getFlashBag()->add('infoDel', 'Salle supprimée :' . $resto);
        return $this->redirectToRoute("guide_michelin_list");
    }

    public function list2(EntityManagerInterface $eM, $etoile)
    {
        $resto = $eM->getRepository(Resto::class)->findBy(["nbEtoile" => $etoile]);
        return $this->render("listCond.html.twig", ["restos" => $resto, "etoile" => $etoile, "inf" => 0, "sup" => 0]);
    }

    public function list3(EntityManagerInterface $eM, $etoile)
    {
        $resto = $eM->getRepository(Resto::class)->FindByStarsMax($etoile);
        return $this->render("listCond.html.twig", ["restos" => $resto, "etoile" => $etoile, "inf" => 1, "sup" => 0]);
    }

    public function list4(EntityManagerInterface $eM, $etoile)
    {
        $resto = $eM->getRepository(Resto::class)->nbOfSup($etoile);
        return $this->render("listCond.html.twig", ["restos" => $resto, "etoile" => $etoile, "inf" => 0, "sup" => 1]);
    }

    public function ajouterEtoile(EntityManagerInterface $eM)
    {
        $eM->getRepository(Resto::class)->addAStar();
        return $this->redirectToRoute("guide_michelin_list");
    }

    public function findByChef(EntityManagerInterface $eM, $chef)
    {
        $resto = $eM->getRepository(Resto::class)->findByChef($chef);
        return $this->render("list.html.twig", ["restos" => $resto]);
    }

    public function listId(EntityManagerInterface $eM)
    {
        $restos = $eM->getRepository(Resto::class)->findAll();
        return $this->render("idList.html.twig", ["restos" => $restos]);
    }


    public function modifier2(EntityManagerInterface $eM, $id, $nom, $nom_chef, $nbEtoile)
    {
        $resto = $eM->getRepository(Resto::class)->find($id);
        $resto->setNom($nom);
        $chef = new Chef();
        $chef->setNom($nom_chef);
        $chef->setPrenom("Steph");
        $resto->setChef($chef);
        $resto->setNbEtoile($nbEtoile);
        $eM->flush();
        return $this->redirectToRoute("guide_michelin_list");
    }
}
