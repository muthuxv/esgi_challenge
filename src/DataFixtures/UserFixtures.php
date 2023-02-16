<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // pwd = test
        $pwd = '$2y$13$r/sNDkWI9w4h0XHSIYqYJusHu3JYZTFwEOxTCkXG31rL9Dy1Tncba';

        $object = (new User())
            ->setEmail('user@user.fr')
            ->setRoles(['ROLE_USER'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Doe')
            ->setFirstName('John')
            ->setCreatedAt(new \DateTimeImmutable('2020-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2020-01-01'))
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('hero@hero.fr')
            ->setRoles(['ROLE_HERO'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Landers')
            ->setFirstName('Mark')
            ->setCreatedAt(new \DateTimeImmutable('2020-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2020-01-01'))
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('admin@user.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Admin')
            ->setFirstName('Admin')
            ->setCreatedAt(new \DateTimeImmutable('2020-01-01'))
            ->setUpdatedAt(new \DateTimeImmutable('2020-01-01'))
        ;
        $manager->persist($object);

        for ($i=0; $i<10; $i++) {
            $object = (new User())
                ->setEmail('user' . $i . '@user.fr')
                ->setRoles(['ROLE_USER'])
                ->setPassword($pwd)
                ->setIsVerified(true)
                ->setLastName('nom' . $i . '')
                ->setFirstName('prenom' . $i . '')
                ->setCreatedAt(new \DateTimeImmutable('2020-01-01'))
                ->setUpdatedAt(new \DateTimeImmutable('2020-01-01'))
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }
}
