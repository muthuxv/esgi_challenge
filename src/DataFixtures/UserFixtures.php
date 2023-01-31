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
            ->setRoles([])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Doe')
            ->setFirstName('John')
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('coach@user.fr')
            ->setRoles(['ROLE_COACH'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Landers')
            ->setFirstName('Mark')
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('client@user.fr')
            ->setRoles(['ROLE_CLIENT'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Dupont')
            ->setFirstName('Jean')
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('admin@user.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd)
            ->setIsVerified(true)
            ->setLastName('Admin')
            ->setFirstName('Admin')
        ;
        $manager->persist($object);

        for ($i=0; $i<50; $i++) {
            $object = (new User())
                ->setEmail('user' . $i . '@user.fr')
                ->setRoles([])
                ->setPassword($pwd)
                ->setIsVerified(true)
                ->setLastName('nom' . $i . 'Doe')
                ->setFirstName('prenom' . $i . 'John')
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }
}
