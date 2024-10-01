<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $wish1 = new Wish();
        $wish1->setName('Nouveau PC');
        $wish1->setContent('Configuration de gamer de fou furieux tkt meme pas 4500€ sous le capot');
        $wish1->setAuthor('Moi-même');
        $wish1->setDuration(1);
        $wish1->setDateCreated(new \DateTimeImmutable('2024-09-27'));
        $wish1->setCategory($this->getReference('category3'));
        $this->addPlayers($wish1);
        $manager->persist($wish1);

        $faker = \Faker\Factory::create('fr_FR');
        $wish2 = new Wish();
        $wish2->setName($faker->word());
        $wish2->setContent($faker->realText());
        $wish2->setAuthor($faker->name());
        $wish2->setDuration($faker->unique()->randomDigit());
        $wish2->setDateCreated(new \DateTimeImmutable('2024-09-30'));
        $wish2->setCategory($this->getReference('category5'));
        $this->addPlayers($wish2);
        $manager->persist($wish2);

        $manager->flush();
    }

  public function getDependencies(): array
  {
    return [CategoryFixtures::class, PlayerFixtures::class];
  }

  private function addPlayers(Wish $wish): void
  {
    for ($i = 0; $i <= mt_rand(0,5); $i++) {
      $player = $this->getReference('player'.rand(1,10));
      $wish->addPlayer($player);
    }
  }
}
