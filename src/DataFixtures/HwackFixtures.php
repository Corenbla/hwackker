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
        $faker = Factory::create();
        $batchSize = 20;

        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 100; $j++) {
                $hwack = new Hwack();
                $hwack
                    ->setIsPrivate($faker->boolean(15))
                    ->setContent($faker->realText(255))
                    ->setAuthor($this->getReference(sprintf(
                        '%s%d',
                        UserFixtures::USER_REFERENCE,
                        $i
                    )))
                ;

                $this->addImage($hwack, $faker, $manager);

                $manager->persist($hwack);
                if ($j % $batchSize === 0) {
                    $manager->flush();
                    $manager->clear();
                }
            }

            $manager->flush();
            $manager->clear();
        }
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
