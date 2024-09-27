<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
      parent::__construct($registry, Wish::class);
    }

    /**
     * @return Wish[] Returns an array of Wish objects
     */
    public function findAllWishes(): array
    {
      return $this->createQueryBuilder('w')
        ->orderBy('w.dateCreated', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findOneById(int $value): ?Wish
    {
      return $this->createQueryBuilder('w')
        ->andWhere('w.id = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function delete(Wish $wish): void
    {
      $this->getEntityManager()->remove($wish);
      $this->getEntityManager()->flush();
    }

    public function update(Wish $wish): ?Wish
    {
      $newWish = $this->findOneById($wish->getId());

      $newWish->setDateModified(new \DateTimeImmutable());
      $newWish->setName($wish->getName());
      $newWish->setAuthor($wish->getAuthor());
      $newWish->setContent($wish->getContent());
      $newWish->setPublished($wish->isPublished());
      $newWish->setDuration($wish->getDuration());

      $this->getEntityManager()->flush();
      return $newWish;
    }
}
