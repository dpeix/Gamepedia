<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GamesDetailsController extends AbstractController
{
    #[Route('/games/details/{id}', name: 'app_games_details')]
    public function index(int $id, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->find($id);

        if (!$game) {
            throw $this->createNotFoundException('Jeu non trouvé');
        }

        return $this->render('games_details/index.html.twig', [
            'game' => $game,
        ]);
    }
}
