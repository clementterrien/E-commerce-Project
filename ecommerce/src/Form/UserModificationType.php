<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sex', ChoiceType::class, array(
                'choices' => array(
                    'M.' => '1',
                    'Mme.' => '0',
                ),
                'expanded' => true,
            ))

            ->add('firstname', null, [
                'attr' => [
                    'class' => 'input-infos-form'
                ]
            ])
            ->add('lastname', null, [
                'attr' => [
                    'class' => 'input-infos-form'
                ]
            ])
            ->add('birthDate', BirthdayType::class, [
                'format' => 'dd-MMMM-yyyy',
                'attr' => [
                    'class' => 'input-infos-form'
                ]
            ])
            ->add('email', null, [
                'attr' => [
                    'class' => 'input-infos-form'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'attr' => [
                    'class' => 'input-infos-form'
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['update'],
        ]);
    }
}