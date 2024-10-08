<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CommentRepository;
use App\Service\Censurator;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WishRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    private WishRepository $wishRepository;
    private FileUploader $fileUploader;
    private CommentRepository $commentRepository;
    public function __construct(WishRepository $wishRepository, FileUploader $fileUploader, CommentRepository $commentRepository) {
      $this->wishRepository = $wishRepository;
      $this->fileUploader = $fileUploader;
      $this->commentRepository = $commentRepository;
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function wishList(): Response
    {
      $wishes = $this->wishRepository->findAllWishes();
      return $this->render('wish/list-wish.html.twig', ['wishes' => $wishes]);
    }

    #[Route('/detail/{id}', name: 'detail', methods: ['GET'])]
    public function wishId(int $id): Response
    {
      $wish = $this->wishRepository->findOneById($id);
      $comments = $this->commentRepository->findCommentsByWishId($id);
      $averageScore = $this->calculateAverageScore($comments);
      return $this->render('wish/detail.html.twig', ['wish' => $wish, 'comments' => $comments, 'averageScore' => $averageScore]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function newWish(Request $request, EntityManagerInterface $em, Censurator $censurator): Response
    {
      try {
        $newWish = new Wish();
        $newWish->setDateCreated(new \DateTimeImmutable());
        $formWish = $this->createForm(WishType::class, $newWish);
        $formWish->handleRequest($request);
        if ($formWish->isSubmitted() && $formWish->isValid()) {
          $imageFile = $formWish->get('image')->getData();
          if ($imageFile) {
            $imageFilename = $this->fileUploader->upload($imageFile);
            $newWish->setImageFilename($imageFilename);
          }
          $newWish->setContent($censurator->purify($newWish->getContent()));
          $newWish->setUser($this->getUser());
          $em->persist($newWish);
          $em->flush();
          $this->addFlash('success', 'Le voeu est bien crée');
          return $this->redirectToRoute('wish_detail', ['id' => $newWish->getId()]);
        }
        return $this->render('wish/new.html.twig', ['formWish' => $formWish]);
      } catch(\Exception $e) {
        $this->addFlash('error', $e->getMessage());
        return $this->render('wish/new.html.twig', ['formWish' => $this->createForm(WishType::class, new Wish())]);
      }
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteWish(Request $request, int $id): Response
    {
      try {
        $wishToDelete = $this->wishRepository->findOneById($id);
        if(!($wishToDelete->getUser()===$this->getUser() ||  $this->isGranted('ROLE_ADMIN'))){
          $this->addFlash('error', 'Vous ne pouvez pas supprimer un objectif dont vous n\'êtes pas l\'auteur');
          return $this->redirectToRoute('wish_list');
        }
        if (!$wishToDelete) {
          throw $this->createNotFoundException(
            'No wish found for id '.$id
          );
        }
        $this->wishRepository->delete($wishToDelete);
        $this->addFlash('success', 'Le voeu est bien supprimé');
        return $this->redirectToRoute('wish_list');
      } catch(\Exception $e) {
        $this->addFlash('error', 'Le voeu n\'a pas pu être supprimé');
        return $this->redirectToRoute('wish_list');
      }
    }

    #[Route('/update/{id}', name: 'update', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_USER')]
    public function updateWish(Request $request, Wish $wish, Censurator $censurator): Response
    {
      try {
        if(!($wish->getUser()===$this->getUser() ||  $this->isGranted('ROLE_ADMIN'))){
          $this->addFlash('error', 'Vous ne pouvez pas modifier un objectif dont vous n\'êtes pas l\'auteur');
          return $this->redirectToRoute('wish_list');
        }
        $formWish = $this->createForm(WishType::class, $wish);
        $formWish->handleRequest($request);
        if ($formWish->isSubmitted() && $formWish->isValid()) {
          $imageFile = $formWish->get('image')->getData();
          if (($formWish->has('deleteImage') && $formWish['deleteImage']->getData()) || $imageFile) {
            $this->fileUploader->delete($wish->getImageFilename(), '/upload/image/wish');
            if ($imageFile) {
              $imageFilename = $this->fileUploader->upload($imageFile);
              $wish->setImageFilename($imageFilename);
            } else {
              $wish->setImageFilename('');
            }
          }
          $wishToUpdate = $this->wishRepository->update($wish, $censurator);
          if (!$wishToUpdate) {
            throw $this->createNotFoundException('No wish found');
          }
          $this->addFlash('success', 'Le voeu est bien modifié');
          return $this->redirectToRoute('wish_detail', ['id' => $wishToUpdate->getId()]);
        }
        return $this->render('wish/edit.html.twig', ['formWish' => $formWish]);
      } catch(\Exception $e) {
        $formWish = $this->createForm(WishType::class, $wish);
        $this->addFlash('error', $e->getMessage());
        return $this->render('wish/edit.html.twig', ['formWish' => $formWish]);
      }
    }

  private function calculateAverageScore(array $comments): ?float
  {
    if (count($comments) === 0) {
      return null;
    }

    $totalScore = 0;
    foreach ($comments as $comment) {
      $totalScore += $comment->getScore();
    }

    return $totalScore / count($comments);
  }
}
