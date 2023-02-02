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

        for ($i=0; $i<10; $i++) {
            $object = (new MissionType())
                ->setName($faker->name)
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}