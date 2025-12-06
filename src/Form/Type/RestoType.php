<?php

namespace App\Form\Type;

use App\Entity\Chef;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Resto;
use App\Repository\ChefRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class)
            ->add('nbEtoile', IntegerType::class)
            ->add(
                'chef',
                EntityType::class,
                [
                    "class" => Chef::class,
                    "query_builder" => function (ChefRepository $repo) {
                        return $repo->createQueryBuilder("c")
                            ->orderBy("c.nom", 'asc');
                    }
                ]
            );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Resto::class,
        ));
    }
}
