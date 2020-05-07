<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('regionCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'block_name' => 'custom_name',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularRegion();
                }
            ])
            ->add('grapeCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularGrapes();
                }
            ])
            ->add('typeCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularTypes();
                }
            ]);

        // ->add('min', NumberType::class, [
        //     'label' => false,
        //     'required' => false,
        //     'attr' => [
        //         'placeholder' => 'Prix Min'
        //     ]
        // ])
        // ->add('max', NumberType::class, [
        //     'label' => false,
        //     'required' => false,
        //     'attr' => [
        //         'placeholder' => 'Prix Max'
        //     ]
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
