<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
      $admin = new User();
      $admin->setUsername('admin');
      $admin->setEmail('admin@admin.fr');
      $password=$this->userPasswordHasher->hashPassword($admin, 'admin');
      $admin->setPassword($password);
      $admin->setRoles(['ROLE_ADMIN']);
      $manager->persist($admin);
      $manager->flush();
      $this->addReference('admin', $admin);
    }
}
