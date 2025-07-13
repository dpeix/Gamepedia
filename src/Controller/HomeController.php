<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use App\Repository\PlatformRepository;
use App\Repository\DeveloperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(
        GameRepository $gameRepository,
        GenreRepository $genreRepository,
        PlatformRepository $platformRepository,
        DeveloperRepository $developerRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $gameRepository->findAll(),
            'genres' => $genreRepository->findAll(),
            'platforms' => $platformRepository->findAll(),
            'developers' => $developerRepository->findAll(),
        ]);
    }
}
