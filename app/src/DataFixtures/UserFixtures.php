<?php
// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setUsername('exampleuser');

        $manager->persist($user);
        $manager->flush();

        // Aggiungi una reference per usarla in altre fixture
        $this->addReference('user_1', $user);
    }
}
