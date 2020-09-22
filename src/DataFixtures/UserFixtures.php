<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Admins
        for ($i = 0; $i < 5; $i++) {
            $email = 'admin' . $i . '@interplanetravels.com';
            $admin = (new User)->setEmail($email)
                ->setRoles(['ROLE_ADMIN'])
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setPhone($faker->e164PhoneNumber);

            $clearPassword = 'admin' . $i;
            $hashedPassword = $this->encoder->encodePassword($admin, $clearPassword);
            $admin->setPassword($hashedPassword);

            $manager->persist($admin);
        }

        // Users
        for ($i = 0; $i < 20; $i++) {
            $email = 'user' . $i . '@mail.org';
            $user = (new User)->setEmail($email)
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setPhone($faker->e164PhoneNumber);

            $clearPassword = 'user' . $i;
            $hashedPassword = $this->encoder->encodePassword($user, $clearPassword);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
