<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class UserFixtures extends Fixture
{
    public const ADMIN = 'ADMIN_USER';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ){}

    public function load(ObjectManager $manager): void
    {
        $faker =  Factory::create('fr_FR');

        $user = new User();
        $user->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@doe.fr')
            ->setUsername('admin')
            ->setIsVerified(true)
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setApiToken('admin_token');
        $this->addReference(self::ADMIN, $user);
        $manager->persist($user);

        for ($i = 0; $i < 20; $i++)
        {
            $user = new User();
            $user->setRoles(['ROLE_USER'])
                ->setEmail($faker->email())
                ->setUsername($faker->userName())
                ->setIsVerified(true)
                ->setPassword($this->hasher->hashPassword($user,'000000'))
                ->setApiToken("user{$i}_token");
            $this->addReference('USER' . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
