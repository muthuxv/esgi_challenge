<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
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
                'label' => 'Description',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'La description doit comporter au moins {{ limit }} caractères'
                    ]),
                ],
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être supérieure ou égale à aujourd\'hui.'
                    ]),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('location', TextType::class, [
                'label' => 'L\'emplacement de l\'évènement',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'L\'emplacement de l\'évènement doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'L\'emplacement de l\'évènement ne peut pas dépasser {{ limit }} caractères'
                    ]),
                ],
            ])
            ->add('filename', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
