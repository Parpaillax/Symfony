<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $wish = new Wish();
        $wish->setName('Nouveau PC');
        $wish->setContent('Configuration de gamer de fou furieux tkt meme pas 4500€ sous le capot');
        $wish->setAuthor('Moi-même');
        $wish->setDuration(1);
        $wish->setDateCreated(new \DateTimeImmutable('2024-09-27'));
        $manager->persist($wish);

        $faker = \Faker\Factory::create('fr_FR');
        $wish = new Wish();
        $wish->setName($faker->word());
        $wish->setContent($faker->realText());
        $wish->setAuthor($faker->name());
        $wish->setDuration($faker->unique()->randomDigit());
        $wish->setDateCreated(new \DateTimeImmutable('2024-09-30'));
        $manager->persist($wish);

        $manager->flush();
    }
}
