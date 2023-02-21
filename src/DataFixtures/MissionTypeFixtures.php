<?php

namespace App\DataFixtures;

use App\Entity\MissionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MissionTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $types = ['Assassinat', 'Sauvetage', 'Espionnage', 'Récupération', 'Protection', 'Autre'];

        foreach ($types as $type) {
            $object = (new MissionType())
                ->setName($type)
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }
}