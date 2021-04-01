<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CountryFixtures extends Fixture
{
    public const COUNTRY_REFERENCE = 'country-';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $country = new Country();
            $country
                ->setName($faker->country)
                ->setIsoCode($faker->countryCode)
            ;

            $this->addReference(sprintf("%s%d", self::COUNTRY_REFERENCE, $i), $country);
            $manager->persist($country);
        }

        $manager->flush();
        $manager->clear();
    }
}
