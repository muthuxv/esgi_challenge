<?php

namespace App\DataFixtures;

use App\Entity\Hero;
use App\Entity\Ability;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class HeroFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //faker
        $faker = Factory::create('fr_FR');

        $abilities = $manager->getRepository(Ability::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        for ($i=0; $i<10; $i++) {
            $object = (new Hero())
                ->setName($faker->word)
                ->setRank($faker->numberBetween(1, 10))
                ->setIsAvailable(1)
                ->addAbility($faker->randomElement($abilities))
                ->setAvatar($faker->imageUrl(200, 200))
                ->setUser($faker->unique()->randomElement($users))
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AbilityFixtures::class,
            UserFixtures::class
        ];
    }

}