<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AssignMission extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hero', EntityType::class, [
                'class' => Hero::class,
                'label' => 'Héro',
                'required' => true,
                'query_builder' => function (EntityRepository $er, ) {
                    return $er->createQueryBuilder('h')
                        ->where('h.isAvailable = true');
                },
                'choice_label' => function ($hero) {
                    $abilityNames = '';
                    foreach ($hero->getAbilities()->toArray() as $ability) {
                        $abilityNames .= $ability->getName() . ', ';
                    }
                    // Remove the last comma and space from the string
                    $abilityNames = substr($abilityNames, 0, -2);
                    return $hero->getName() . ' (' . $abilityNames . ')';
                },                
                'invalid_message' => 'Tu dois choisir un héros',
                'placeholder' => 'Choisir un héros',
                'attr' => [
                    'class' => 'form-control p-2 border-2 border-gray-300 rounded-lg'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
