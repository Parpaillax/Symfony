<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Wish;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comment', name:'comment_')]
class CommentController extends AbstractController
{
  private CommentRepository $commentRepository;
  private WishRepository $wishRepository;

  public function __construct(CommentRepository $commentRepository, WishRepository $wishRepository){
    $this->commentRepository = $commentRepository;
    $this->wishRepository = $wishRepository;
  }
    #[Route('/new/{wishId}', name: 'new', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_USER')]
    public function newComment(Request $request, EntityManagerInterface $em, int $wishId): Response
    {
      $newComment = new Comment();
      $formComment = $this->createForm(CommentType::class, $newComment);
      $formComment->handleRequest($request);
      if($formComment->isSubmitted() && $formComment->isValid()){
        $newComment->setUser($this->getUser());
        $wish = $this->wishRepository->findOneById($wishId);
        $newComment->setWish($wish);
        $em->persist($newComment);
        $em->flush();
        $this->addFlash('success', 'Commentaire bien ajouté à l\'objectif');
        return $this->redirectToRoute('wish_detail', ['id' => $newComment->getWish()->getId()]);
      }
      return $this->render('comment/new.html.twig', ['formComment' => $formComment]);
    }

    #[Route('/update/{id}', name: 'update', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_USER')]
    public function updateComment(Request $request, Comment $comment): Response
    {
      try {
        if(!($comment->getUser()===$this->getUser() ||  $this->isGranted('ROLE_ADMIN'))){
          $this->addFlash('error', 'Vous ne pouvez pas modifier un commentaire dont vous n\'êtes pas l\'auteur');
          return $this->redirectToRoute('wish_detail', ['id' => $comment->getWish()->getId()]);
        }
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        if($formComment->isSubmitted() && $formComment->isValid()){
          $commentToUpdate = $this->commentRepository->update($comment);
          if (!$commentToUpdate) {
            throw $this->createNotFoundException('Impossible de modifier le commentaire');
          }
          $this->addFlash('success', 'Commentaire modifié avec succès');
          return $this->redirectToRoute('wish_detail', ['id' => $comment->getWish()->getId()]);
        }
        return $this->render('comment/edit.html.twig', ['formComment' => $formComment]);
      } catch(\Exception $e) {
        $formComment = $this->createForm(CommentType::class, $comment);
        $this->addFlash('danger', $e->getMessage());
        return $this->render('comment/edit.html.twig', ['formComment' => $formComment]);
      }
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteComment(Request $request, Comment $comment): Response
    {
      try {
        $commentToDelete = $this->commentRepository->fineOneById($comment->getId());
        if(!($commentToDelete->getUser()===$this->getUser() ||  $this->isGranted('ROLE_ADMIN'))){
          $this->addFlash('error', 'Vous ne pouvez pas supprimer un commentaire dont vous n\'êtes pas l\'auteur');
          return $this->redirectToRoute('wish_detail', ['id' => $comment->getWish()->getId()]);
        }
        if (!$commentToDelete) {
          throw $this->createNotFoundException('Impossible de supprimer un commentaire');
        }
        $this->commentRepository->delete($commentToDelete);
        $this->addFlash('success', 'Le commentaire est bien supprimé');
        return $this->redirectToRoute('wish_detail', ['id' => $comment->getWish()->getId()]);
      } catch(\Exception $e) {
        $this->addFlash('danger', $e->getMessage());
        return $this->redirectToRoute('wish_detail', ['id' => $comment->getWish()->getId()]);
      }
    }

}
