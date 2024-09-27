<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WishRepository;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    private WishRepository $wishRepository;
    public function __construct(WishRepository $wishRepository) {
      $this->wishRepository = $wishRepository;
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
      return $this->render('wish/detail.html.twig', ['wish' => $wish]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newWish(Request $request, EntityManagerInterface $em): Response
    {
      $newWish = new Wish();
      $formWish = $this->createForm(WishType::class, $newWish);
      $formWish->handleRequest($request);
      if ($formWish->isSubmitted() && $formWish->isValid()) {
        $em->persist($newWish);
        $em->flush();
        $this->addFlash('success', 'Le voeu est bien crée');
        return $this->redirectToRoute('wish_detail', ['id' => $newWish->getId()]);
      }
      return $this->render('wish/new.html.twig', ['formWish' => $formWish]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteWish(Request $request, int $id): Response
    {
      $wishToDelete = $this->wishRepository->findOneById($id);
      if (!$wishToDelete) {
        throw $this->createNotFoundException(
          'No wish found for id '.$id
        );
      }
      $this->wishRepository->delete($wishToDelete);
      return $this->redirectToRoute('wish_list');
    }

    #[Route('/update/{id}', name: 'update', methods: ['POST', 'GET'])]
    public function updateWish(Request $request, Wish $wish): Response
    {
      $formWish = $this->createForm(WishType::class, $wish);
      $formWish->handleRequest($request);
      if ($formWish->isSubmitted() && $formWish->isValid()) {
        $wishToUpdate = $this->wishRepository->update($wish);
        if (!$wishToUpdate) {
          throw $this->createNotFoundException('No wish found');
        }
        $this->addFlash('success', 'Le voeu est bien modifié');
        return $this->redirectToRoute('wish_detail', ['id' => $wishToUpdate->getId()]);
      }
      return $this->render('wish/edit.html.twig', ['formWish' => $formWish]);
    }
}
