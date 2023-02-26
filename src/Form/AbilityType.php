<?php

namespace App\Form;

use App\Entity\Ability;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class AbilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Le nom de l\'abilité',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'La description',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'La description doit comporter au moins {{ limit }} caractères'
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ability::class,
        ]);
    }
}
