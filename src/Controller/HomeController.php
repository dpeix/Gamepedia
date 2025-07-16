<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use App\Repository\PlatformRepository;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        GameRepository $gameRepository,
        GenreRepository $genreRepository,
        PlatformRepository $platformRepository,
        DeveloperRepository $developerRepository,
        Request $request
    ): Response {
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = 8;
        $offset = ($page - 1) * $limit;

        $qb = $gameRepository->createQueryBuilder('g')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb);
        $totalGames = count($paginator);
        $totalPages = (int)ceil($totalGames / $limit);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $paginator,
            'genres' => $genreRepository->findAll(),
            'platforms' => $platformRepository->findAll(),
            'developers' => $developerRepository->findAll(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
