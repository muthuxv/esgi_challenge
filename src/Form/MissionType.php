<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\MissionType as  MT;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('location')
            ->add('result')
            ->add('missionType', EntityType::class, [
                'class' => MT::class,
                'label' => 'Type de mission: ',
                'required' => true,
                'choice_label' => function(MT $mt) {
                    return $mt->getName();
                },
                'invalid_message' => 'Tu dois choisir un type',
                'placeholder' => 'Choisir un type',
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
