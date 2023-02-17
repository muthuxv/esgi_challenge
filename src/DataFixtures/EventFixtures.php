<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Hero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();
        $heroes = $manager->getRepository(Hero::class)->findAll();

        for ($i=0; $i<10; $i++) {
            $object = (new Event())
                ->setName($faker->name)
                ->setDescription($faker->text)
                ->setDate('2021-01-01')
                ->setLocation($faker->city)
                ->setPrice($faker->numberBetween(1, 100))
                ->addUser($faker->unique()->randomElement($users))
                ->addHero($faker->unique()->randomElement($heroes))
            ;
    
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            HeroFixtures::class
            
        ];
    }
}