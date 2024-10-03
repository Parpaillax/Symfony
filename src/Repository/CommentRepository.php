<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class CommentRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Comment::class);
  }

  public function fineOneById(int $id): Comment
  {
    return $this->createQueryBuilder('c')
      ->where('c.id = :id')
      ->setParameter('id', $id)
      ->getQuery()
      ->getOneOrNullResult();
  }

  public function findAllComment(): Paginator
  {
    $qb = $this->createQueryBuilder('c')
      ->addSelect('user')
      ->addSelect('wish')
      ->leftJoin('c.user', 'user')
      ->leftJoin('c.wish', '$wish')
      ->orderBy('c.dateCreated', 'ASC')
      ->getQuery();

    return new Paginator($qb);
  }

  public function findCommentsByWishId(int $idWish): array
  {
    return $this->createQueryBuilder('c')
      ->leftJoin('c.wish', 'wish')
      ->where('wish.id = :idWish')
      ->setParameter('idWish', $idWish)
      ->getQuery()
      ->getResult();
  }

  public function delete(Comment $comment): void
  {
    $this->getEntityManager()->remove($comment);
    $this->getEntityManager()->flush();
  }

  public function update(Comment $comment): ?Comment
  {
    $newComment = $this->fineOneById($comment->getId());
    $newComment->setDateUpdated(new \DateTimeImmutable());
    $newComment->setContent($comment->getContent());
    $newComment->setScore($comment->getScore());

    $this->getEntityManager()->flush();
    return $newComment;
  }


}