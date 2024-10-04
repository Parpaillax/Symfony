<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
  private WishRepository $wishRepository;
  private HttpClientInterface $httpClient;
  public function __construct(WishRepository $wishRepository, HttpClientInterface $httpClient) {
    $this->wishRepository = $wishRepository;
    $this->httpClient = $httpClient;
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

    #[Route('/events', name: "events", methods: ['GET'])]
    public function eventsPage(): Response
    {
      $response = $this->httpClient->request('GET', 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/evenements-publics-openagenda/records', [
        'query' => [
          'limit' => 18,
          'offset' => 1
        ]
      ]);
      // Vérifie le statut de la réponse et récupère les données
      if ($response->getStatusCode() === 200) {
        $totalCount = $response->toArray()['total_count'];
        $totalPages = $totalCount / 18;
        $content = $response->toArray(); // Décode directement en tableau associatif
        return $this->render("main/events.html.twig", [
          'events' => $content['results'] ?? [],
          'totalPages' => $totalPages,
        ]);
      }
//      dd($content);
    }
}