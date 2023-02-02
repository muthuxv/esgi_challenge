<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\EventPayment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i<10; $i++) {
            $object = (new Event())
                ->setName($faker->name)
                ->setDescription($faker->paragraph)
                ->setDate($faker->dateTimeBetween('-1 years', 'now'))
                ->setLocation($faker->city)
            ;
    
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EventPaymentFixtures::class
        ];
    }
}