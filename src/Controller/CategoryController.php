<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
  private CategorieRepository $repository;

  public function __construct(CategorieRepository $repository) {
    $this->repository = $repository;
  }

  #[Route('/list', name: 'list')]
  public function index(): Response
  {
      return $this->render('category/index.html.twig', [
          'controller_name' => 'CategoryController',
      ]);
  }

  #[Route('/delete/{id}', name: 'delete', methods: ['GET'])]
  public function delete(Categorie $categorie, Request $request): Response
  {
    try {
      $this->repository->delete($categorie);
      $this->addFlash('success', 'La catégorie est bien supprimée');
      return $this->redirectToRoute('wish_list');
    } catch(\Exception $e) {
      $this->addFlash('danger', $e->getMessage());
      return $this->redirectToRoute('wish_list');
    }
  }
}
