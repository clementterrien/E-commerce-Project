<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Error;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class SearchType extends AbstractType
{
    protected $searchData;
    protected $regionCategories;
    protected $categoryName;

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
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularCategory('region');
                }
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $this->addDefaultCategories($form);
                }
            )
            ->get('regionCategories')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    /* @var Category[] $regionCategories */
                    $regionCategories = $event->getForm()->getData();
                    if ($regionCategories) {
                        $this->ActualizeFormWhenRegionsSubmited($form, $regionCategories);
                    }
                }
            );

        $builder
            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Prix Min'
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix Max'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    /**
     * getBlockPrefix
     *
     * @return void
     */
    public function getBlockPrefix()
    {
        return '';
    }

    /**
     * addGrapeCategories add the Grape Categories field in the form if the form hasn't been submitted. 
     * It don't add all Grape Categories but mostpopular ones
     *
     * @param  FormInterface $form
     * @return void
     */
    public function addDefaultCategories(FormInterface $form): void
    {
        $form
            ->add('grapeCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularCategory('grape', 20);
                }
            ])
            ->add('designationCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularCategory('designation', 20);
                }
            ])
            ->add('typeCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularCategory('type', 1);
                }
            ])
            ->add('alcoolCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularCategory('alcool', 2);
                }
            ])
            ->add('literCategories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->findPopularCategory('liter', 2);
                }
            ]);
    }

    /**
     * addGrapesByRegionSearch
     *
     * @param  FormInterface $form
     * @param  array $regionCategories
     * @return void
     */
    public function addGrapesByRegionSearch(FormInterface $form, array $regionCategories)
    {
        $this->searchData = $regionCategories;

        $form->getParent()->add('grapeCategories', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Category::class,
            'expanded' => true,
            'multiple' => true,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->findGrapesByRegions($this->searchData);
            }
        ]);
    }

    /**
     * findCategoryByRegions
     *
     * @param  mixed $form
     * @param  mixed $categoryName
     * @param  mixed $regionCategories
     * @return void
     */
    public function findCategoryByRegions(FormInterface $form, string $categoryName, array $regionCategories)
    {
        $this->categoryName = $categoryName;
        $this->regionCategories = $regionCategories;

        $form->getParent()->add($categoryName . 'Categories', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Category::class,
            'expanded' => true,
            'multiple' => true,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->findCategoryByRegions($this->categoryName, $this->regionCategories);
            }
        ]);
    }

    /**
     * onSubmitData 
     *
     * @param  mixed $form
     * @param  Category[] $searchData
     * @return void
     */
    public function actualizeFormWhenRegionsSubmited(FormInterface $form, array $regionCategories): void
    {
        if (!empty($regionCategories)) {
            $this->findCategoryByRegions($form, 'designation', $regionCategories);
            $this->findCategoryByRegions($form, 'grape', $regionCategories);
        } else {
            throw new Error('You\'re trying to change categories to match with a region but no region has been precised');
        }
    }
}
