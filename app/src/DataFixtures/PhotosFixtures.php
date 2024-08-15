<?php

namespace App\DataFixtures;

use App\Entity\Photos;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PhotosFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Ottieni l'utente usando la reference
        $user = $this->getReference('user_1'); // Usa la reference definita in UserFixtures

        // Creazione di una foto e associazione all'utente
        $photo = new Photos();
        $photo->setTitle('Ciccione');
        $photo->setDescription('This is a description.');
        $photo->setImagePath('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRXJA32WU4rBpx7maglqeEtt3ot1tPIRWptxA&s');
        $photo->addUser($user); // Associare la foto all'utente

        $manager->persist($photo);

        // Aggiungi una reference per usarla in altre fixture
        $this->addReference('photo_1', $photo);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class, // Assicurati che UserFixtures venga eseguito prima di PhotosFixtures
        ];
    }
}