<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category1 = new Categorie();
        $category1->setName('Voyage');
        $category1->setDateCreated(new \DateTimeImmutable());
        $manager->persist($category1);
        $this->addReference('category1', $category1);

        $category2 = new Categorie();
        $category2->setName('Sport');
        $category2->setDateCreated(new \DateTimeImmutable());
        $manager->persist($category2);
        $this->addReference('category2', $category2);

        $category3 = new Categorie();
        $category3->setName('Developpement');
        $category3->setDateCreated(new \DateTimeImmutable());
        $manager->persist($category3);
        $this->addReference('category3', $category3);

        $category4 = new Categorie();
        $category4->setName('Motivation');
        $category4->setDateCreated(new \DateTimeImmutable());
        $manager->persist($category4);
        $this->addReference('category4', $category4);

        $category5 = new Categorie();
        $category5->setName('Autre');
        $category5->setDateCreated(new \DateTimeImmutable());
        $manager->persist($category5);
        $this->addReference('category5', $category5);

        $manager->flush();
    }
}
