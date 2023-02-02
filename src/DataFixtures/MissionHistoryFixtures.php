<?php

namespace App\DataFixtures;

use App\Entity\MissionHistory;
use App\Entity\Mission;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MissionHistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $mission = $manager->getRepository(Mission::class)->findAll();
        $user = $manager->getRepository(User::class)->findAll();

        $object = (new MissionHistory())
            ->setMission($mission)
            ->setComment('Commentaire 1')
            ->setStatus('En cours')
            ->setUpdateAt(new \DateTimeImmutable('2021-01-01'))
            ->setUpdateBy($user);
        $manager->persist($object);

        $object = (new MissionHistory())
            ->setMission($mission)
            ->setComment('Commentaire 2')
            ->setStatus('En cours')
            ->setUpdateAt(new \DateTimeImmutable('2021-01-01'))
            ->setUpdateBy($user);
        $manager->persist($object);

        $object = (new MissionHistory())
            ->setMission($mission)
            ->setComment('Commentaire 3')
            ->setStatus('En cours')
            ->setUpdateAt(new \DateTimeImmutable('2021-01-01'))
            ->setUpdateBy($user);
        $manager->persist($object);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MissionFixtures::class,
            UserFixtures::class
        ];
    }
}