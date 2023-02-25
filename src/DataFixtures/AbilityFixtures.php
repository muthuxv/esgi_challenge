<?php

namespace App\DataFixtures;

use App\Entity\Ability;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AbilityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $abilities = [
            [
                'name' => 'Super-force',
                'description' => 'Capacité à avoir une force physique surhumaine, capable de soulever des objets très lourds, détruire des obstacles avec facilité et combattre des ennemis avec une force décuplée.',
            ],
            [
                'name' => 'Invisibilité',
                'description' => 'Pouvoir de devenir invisible, ce qui permet à l\'individu de se déplacer sans être vu et d\'éviter les conflits.',
            ],
            [
                'name' => 'Vol',
                'description' => 'Capacité à voler dans les airs, ce qui permet à l\'individu de se déplacer rapidement et d\'éviter les obstacles au sol.',
            ],
            [
                'name' => 'Téléportation',
                'description' => 'Pouvoir de se déplacer instantanément d\'un endroit à un autre, ce qui permet à l\'individu d\'éviter les dangers et de se déplacer rapidement.',
            ],
            [
                'name' => 'Contrôle du feu',
                'description' => 'Pouvoir de générer et de contrôler le feu, ce qui permet à l\'individu de créer des boules de feu, de causer des incendies et de manipuler la chaleur.',
            ],
            [
                'name' => 'Contrôle de la glace',
                'description' => 'Pouvoir de générer et de contrôler la glace, ce qui permet à l\'individu de créer des objets en glace, de causer des tempêtes de neige et de manipuler la température.',
            ],
            [
                'name' => 'Télékinésie',
                'description' => 'Pouvoir de déplacer des objets avec l\'esprit, ce qui permet à l\'individu de soulever des objets à distance et de les déplacer sans les toucher.',
            ],
            [
                'name' => 'Pouvoir de guérison',
                'description' => 'Capacité à guérir les blessures et les maladies, ce qui permet à l\'individu de soigner les autres et de se guérir rapidement.',
            ],
            [
                'name' => 'Super-vitesse',
                'description' => 'Capacité à se déplacer à des vitesses très élevées, ce qui permet à l\'individu de se déplacer rapidement et d\'éviter les obstacles.',
            ],
            [
                'name' => 'Super-endurance',
                'description' => 'Capacité à maintenir une activité physique intensive pendant une longue période de temps, ce qui permet à l\'individu de résister à la fatigue et de continuer à se battre.',
            ],
            [
                'name' => 'Régénération rapide',
                'description' => 'Capacité à guérir rapidement les blessures et à régénérer les tissus corporels, ce qui permet à l\'individu de se guérir rapidement et de récupérer de ses blessures.',
            ],
            [
                'name' => 'Métamorphose',
                'description' => 'Pouvoir de changer d\'apparence, ce qui permet à l\'individu de se camoufler et de se cacher.',
            ],
            [
                'name' => 'Super-intelligence',
                'description' => 'Capacité à avoir une intelligence supérieure à la moyenne, ce qui permet à l\'individu de résoudre des problèmes complexes et de trouver des solutions rapidement.',
            ],
            [
                'name' => 'Contrôle mental',
                'description' => 'Pouvoir de contrôler l\'esprit des autres, ce qui permet à l\'individu de manipuler les pensées et les actions de ses ennemis.',
            ],
            [
                'name' => 'Création d\'illusions',
                'description' => 'Pouvoir de créer des illusions, ce qui permet à l\'individu de tromper les sens des autres et de les confondre.',
            ],
            [
                'name' => 'Pouvoir de lévitation',
                'description' => 'Capacité à léviter dans les airs, ce qui permet à l\'individu de se déplacer sans toucher le sol.',
            ],
            [
                'name' => 'Vision nocturne',
                'description' => 'Capacité à voir dans l\'obscurité, ce qui permet à l\'individu de se déplacer facilement dans les endroits sombres et de repérer ses ennemis.',
            ],
            [
                'name' => 'Super-sensibilité',
                'description' => 'Capacité à avoir des sens surdéveloppés, ce qui permet à l\'individu de percevoir des choses que les autres ne peuvent pas.',
            ],
            [
                'name' => 'Incorporalité',
                'description' => 'Capacité à devenir intangible et à traverser les objets solides, ce qui permet à l\'individu d\'éviter les obstacles et de se cacher facilement.',
            ],
            [
                'name' => 'Invocation d\'esprits',
                'description' => 'Pouvoir de communiquer avec les esprits et de les invoquer, ce qui permet à l\'individu d\'avoir des alliés surnaturels.',
            ],
        ];
        
        foreach ($abilities as $ability) {
            $object = (new Ability())
                ->setName($ability['name'])
                ->setDescription($ability['description'])
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}