<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlayerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $faker = \Faker\Factory::create('fr_FR');
      for ($i = 0; $i < 10; $i++) {
        $player = new Player();
        $player->setFirstName($faker->firstName());
        $player->setLastName($faker->lastName());
        $player->setDateCreated(new \DateTimeImmutable());
        $manager->persist($player);
        $this->addReference('player'.$i, $player);
      }
      $manager->flush();
    }
}
