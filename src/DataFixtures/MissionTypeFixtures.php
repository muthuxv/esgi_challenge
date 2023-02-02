<?php

namespace App\DataFixtures;

use App\Entity\MissionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MissionTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $object = (new MissionType())
            ->setName('Type 1');
        $manager->persist($object);

        $object = (new MissionType())
            ->setName('Type 2');
        $manager->persist($object);

        $object = (new MissionType())
            ->setName('Type 3');
        $manager->persist($object);

        $manager->flush();
    }
}