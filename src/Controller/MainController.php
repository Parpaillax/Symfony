<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
  private WishRepository $wishRepository;
  public function __construct(WishRepository $wishRepository) {
    $this->wishRepository = $wishRepository;
  }

    #[Route('/', name: "main_home", methods: ['GET'])]
    public function home(): Response
    {
      $wishList = $this->wishRepository->findAll();
      return $this->render("main/home.html.twig", ['wishList' => $wishList]);
    }

    #[Route('/about-us', name:"about_us", methods: ['GET'])]
    public function aboutUsPage(): Response
    {
        return $this->render("main/about-us.html.twig");
    }
}