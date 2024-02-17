<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CountryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fake = Faker\Factory::create();
        $countries = [];

        for ($i = 0; $i < 20; $i++) {
            $country = new Country();
            while ($country_title = $fake->country()) {
                if (empty($country_title) || in_array($country_title, $countries)) continue;
                $country->setTitle($country_title);
                break;
            }

            array_push($countries, $country_title);
            $manager->persist($country);
        }

        $manager->flush();
    }
}
