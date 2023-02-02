<?php

namespace App\DataFixtures;

use App\Entity\EventPayment;
use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventPaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $events = $manager->getRepository(Event::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        for ($i=0; $i<10; $i++) {
            $object = (new EventPayment());
            for ($y=0; $y<$faker->numberBetween(1, 10); $y++) {
                $object->setEvent($faker->randomElement($events));
            }
            for($j=0; $j<$faker->numberBetween(1, 10); $j++) {
                $object->setUser($faker->randomElement($users));
            }

            // $object = (new EventPayment())
            //     ->setEvent($faker->randomElement($events))
            //     ->setUser($faker->randomElement($users));

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EventFixtures::class,
            UserFixtures::class
        ];
    }
}