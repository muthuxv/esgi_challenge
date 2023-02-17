<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\User;
use App\Entity\Hero;
use App\Entity\MissionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class MissionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();
        $heroes = $manager->getRepository(Hero::class)->findAll();
        $missionTypes = $manager->getRepository(MissionType::class)->findAll();

        for ($i=0; $i<10; $i++) {
            $object = (new Mission())
                ->setUser($faker->randomElement($users))
                ->setHero($faker->randomElement($heroes))
                ->setMissionType($faker->randomElement($missionTypes))
                ->setName($faker->word)
                ->setDescription($faker->text)
                ->setLocation($faker->address)
                ->setResult($faker->text)
                ->setStatus($faker->randomElement(['pending', 'in_progress', 'accepted', 'refused', 'completed']))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setDateEnd(new \DateTimeImmutable())
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MissionTypeFixtures::class,
            HeroFixtures::class,
            UserFixtures::class
            
        ];
    }
}