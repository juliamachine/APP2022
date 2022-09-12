<?php

/**
 * User Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * UserFixtures class..
 */
class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Load function.
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("admin@example.com");
        $user->setPassword($this->hasher->hashPassword($user, "password"));

        $manager->persist($user);
        $manager->flush();
    }
}
