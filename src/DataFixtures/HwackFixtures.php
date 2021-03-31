<?php

namespace App\DataFixtures;

use App\Entity\Hwack;
use App\Entity\HwackImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class HwackFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $hwack = new Hwack();
            $hwack
                ->setIsPrivate($faker->boolean(15))
                ->setContent($faker->realText(255))
                ->setAuthor(
                    $this->getReference(sprintf(
                        '%s%d',
                        UserFixtures::USER_REFERENCE,
                        random_int(0, 9)
                    ))
                )
            ;

            $this->addImage($hwack, $faker, $manager);

            $manager->persist($hwack);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    private function addImage(Hwack $hwack, Generator $faker, ObjectManager $manager)
    {
        $hwackImage = new HwackImage();
        $hwackImage
            ->setFilename($faker->imageUrl())
            ->setHwack($hwack)
        ;

        $manager->persist($hwackImage);
        $hwack->setHwackImage($hwackImage);
    }
}
