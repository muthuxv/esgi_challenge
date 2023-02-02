<?php

namespace App\DataFixtures;

use App\Entity\Hero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HeroFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // pwd = test
        $pwd = '$2y$13$r/sNDkWI9w4h0XHSIYqYJusHu3JYZTFwEOxTCkXG31rL9Dy1Tncba';

        $object = (new Hero())
            ->setEmail('hero1@hero.fr')
            ->setRoles(['ROLE_HERO'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Landers')
            ->setFirstName('Mark')
            ->setHeroName('Hero 1')
            ->setRank(1)
            ->setAvailability(true)
            ->setAvatar('hero1.png')
            ->setCreatedAt(new \DateTimeImmutable('2020-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2020-01-01'));
        $manager->persist($object);

        $object = (new Hero())
            ->setEmail('hero2@hero.fr')
            ->setRoles(['ROLE_HERO'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Landers')
            ->setFirstName('Mark')
            ->setHeroName('Hero 2')
            ->setRank(2)
            ->setAvailability(true)
            ->setAvatar('hero2.png')
            ->setCreatedAt(new \DateTimeImmutable('2020-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2020-01-01'));
        $manager->persist($object);

        $object = (new Hero())
            ->setEmail('hero3@hero.fr')
            ->setRoles(['ROLE_HERO'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Landers')
            ->setFirstName('Mark')
            ->setHeroName('Hero 3')
            ->setRank(3)
            ->setAvailability(true)
            ->setAvatar('hero3.png')
            ->setCreatedAt(new \DateTimeImmutable('2020-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2020-01-01'));
        $manager->persist($object);

        $manager->flush();
    }
}