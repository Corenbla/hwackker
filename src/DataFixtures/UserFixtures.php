<?php

namespace App\DataFixtures;

use App\Entity\ProfilePicture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER_REFERENCE = 'user-';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();


            $user
                ->setUsername($i !== 0 ? $faker->userName : 'admin')
                ->setBirthDate($faker->dateTime('10 years ago'))
                ->setFacebookUrl($faker->url)
                ->setTwitterUrl($faker->url)
                ->setEmail($faker->email)
                ->setIsAdmin($i === 0)
                ->setCountry(
                    $this->getReference(CountryFixtures::COUNTRY_REFERENCE . rand(0,4))
                )
            ;

            $this->addImage($user, $faker, $manager);

            $user->setPassword($this->passwordEncoder->encodePassword($user, '1234'));

            $this->setReference(sprintf('%s%d', self::USER_REFERENCE, $i), $user);

            $manager->persist($user);
        }

        $manager->flush();
        $manager->clear();
    }

    private function addImage(User $user, Generator $faker, ObjectManager $manager)
    {
        $profilePicture = new ProfilePicture();
        $profilePicture
            ->setFilename($faker->imageUrl())
            ->setOwner($user)
        ;
        $manager->persist($profilePicture);

        $user->setProfilePicture($profilePicture);
    }

    public function getDependencies()
    {
        return [
            CountryFixtures::class,
        ];
    }
}
