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
        
        for ($i=0; $i<10; $i++) {
            $object = (new Ability())
                ->setName($faker->name)
                ->setDescription($faker->text)
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}