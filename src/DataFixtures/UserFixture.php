<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    public function __construct(
        private userPasswordHasherInterface $passwordHasher
    )
    {}

    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 50; $i++){
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password')
            );
            if ($i === 1){
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }
            $manager->persist($user);

        }


        $manager->flush();
    }
}
