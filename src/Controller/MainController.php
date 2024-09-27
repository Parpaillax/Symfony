<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: "main_home", methods: ['GET'])]
    public function home(): Response
    {
        return $this->render("main/home.html.twig");
    }

    #[Route('/about-us', name:"about_us", methods: ['GET'])]
    public function aboutUsPage(): Response
    {
        return $this->render("main/about-us.html.twig");
    }
}