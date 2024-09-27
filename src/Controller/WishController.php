<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\WishRepository;

class WishController extends AbstractController
{
    private WishRepository $wishRepository;
    public function __construct(WishRepository $wishRepository) {
        $this->wishRepository = $wishRepository;
    }

    #[Route('/wish', name: 'wish_list', methods: ['GET'])]
    public function wishList(): Response
    {
        $wishes = $this->wishRepository->findAllWishes();
        return $this->render('wish/list-wish.html.twig', ['wishes' => $wishes]);
    }

    #[Route('/wish/{id}', name: 'wish_detail', methods: ['GET'])]
    public function wishId(int $id): Response
    {
        $wish = $this->wishRepository->findOneById($id);
        return $this->render('wish/detail.html.twig', ['wish' => $wish]);
    }

    #[Route('/wish/new', name: 'wish_new', methods: ['GET', 'POST'])]
    public function newWish(): Response
    {
        return $this->render('wish/new.html.twig');
    }
}
