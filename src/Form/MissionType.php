<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\MissionType as  MT;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la mission',
                'required'   => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom de la mission doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Le nom de la mission ne peut pas dépasser {{ limit }} caractères'
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required'   => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'La description doit comporter au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Emplacement de votre mission',
                'required'   => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'L\'emplacement de la mission doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'L\'emplacement de la mission ne peut pas dépasser {{ limit }} caractères'
                    ]),
                ],
            ])
            ->add('result', TextType::class, [
                'label' => 'Résultat de la mission',
                'required'   => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le résultat doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Le résultat ne peut pas dépasser {{ limit }} caractères'
                    ]),
                ],
            ])
            ->add('missionType', EntityType::class, [
                'class' => MT::class,
                'label' => 'Type de mission',
                'required' => true,
                'choice_label' => function(MT $mt) {
                    return $mt->getName();
                },
                'invalid_message' => 'Tu dois choisir un type',
                'placeholder' => 'Choisir un type',
                'attr' => [
                    'class' => 'form-control p-2 border-2 border-gray-300 rounded-lg'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
