<?php

namespace App\DataFixtures;

use App\Entity\President;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PresidentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fake = Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            // Presidents
            $president = new President();
            $president->setName($fake->firstName());
            $president->setSurname($fake->lastName());
            $manager->persist($president);
        }

        $manager->flush();
    }
}
