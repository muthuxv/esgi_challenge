<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\User;
use App\Entity\Hero;
use App\Entity\MissionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MissionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $manager->getRepository(User::class)->findAll();
        $hero = $manager->getRepository(Hero::class)->findAll();
        $missionType = $manager->getRepository(MissionType::class)->findAll();

        $object = (new Mission())
            ->setUser($user)
            ->setMissionType($missionType)
            ->setHero($hero)
            ->setName('Mission 1')
            ->setDescription('Description 1')
            -setLocation('Paris')
            -setResult('Sauver le chat')
            ->setStatus('En cours')
            ->setCreatedAt(new \DateTimeImmutable('2021-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2021-01-01'));
        $manager->persist($object);

        $object = (new Mission())
            ->setUser($user)
            ->setMissionType($missionType)
            ->setHero($hero)
            ->setName('Mission 2')
            ->setDescription('Description 2')
            -setLocation('Londres')
            -setResult('Sauver le chien')
            ->setStatus('En cours')
            ->setCreatedAt(new \DateTimeImmutable('2021-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2021-01-01'));
        $manager->persist($object);

        $object = (new Mission())
            ->setUser($user)
            ->setMissionType($missionType)
            ->setHero($hero)
            ->setName('Mission 3')
            ->setDescription('Description 3')
            -setLocation('Lyon')
            -setResult('Sauver le lapin')
            ->setStatus('En cours')
            ->setCreatedAt(new \DateTimeImmutable('2021-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2021-01-01'));
        $manager->persist($object);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            HeroFixtures::class,
            UserFixtures::class,
            MissionTypeFixtures::class
        ];
    }
}