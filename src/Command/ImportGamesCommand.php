<?php
namespace App\Command;

use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Developer;
use App\Service\RawgApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-games', //php bin/console app:import-games
    description: 'Importe des jeux depuis l’API RAWG',
)]
class ImportGameCommand extends Command
{
    public function __construct(
        private RawgApiService $rawgApiService,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Importe les jeux depuis une source externe');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '512M'); // ou plus si besoin
        $output->writeln('Import des jeux depuis RAWG...');

        $page = 1;
        $importedCount = 0;
        $maxPages = 10;

        do {
            $gamesData = $this->rawgApiService->fetchGames($page);
            $results = $gamesData['results'] ?? [];
            if (empty($results)) {
                break;
            }

            foreach ($results as $gameData) {
                // Vérifier si le jeu existe déjà
                $existingGame = $this->em->getRepository(Game::class)
                    ->findOneBy(['rawgId' => $gameData['id']]);

                if ($existingGame) {
                    $output->writeln("Le jeu {$gameData['name']} existe déjà.");
                    continue;
                }

                // Récupérer les détails du jeu pour avoir la description complète
                $gameDetails = $this->rawgApiService->fetchGameDetails($gameData['id']);

                // Créer le jeu
                $game = new Game();
                $game->setRawgId($gameData['id']);
                $game->setName($gameData['name']);
                $game->setDescription($gameDetails['description_raw'] ?? null);
                $game->setReleased(
                    isset($gameData['released']) ? new \DateTime($gameData['released']) : null
                );
                $game->setBackgroundImage($gameData['background_image']);
                $game->setRating($gameData['rating']);
                $game->setRatingCount($gameData['ratings_count']);
                $game->setPlaytime($gameData['playtime']);

                // Ajouter les genres
                foreach ($gameData['genres'] as $genreData) {
                    $genre = $this->em->getRepository(Genre::class)
                        ->findOneBy(['rawgId' => $genreData['id']]);

                    if (!$genre) {
                        $genre = new Genre();
                        $genre->setRawgId($genreData['id']);
                        $genre->setName($genreData['name']);
                        $this->em->persist($genre);
                    }

                    $game->addGenre($genre);
                }

                // Ajouter les plateformes
                foreach ($gameData['platforms'] as $platformData) {
                    $platformName = $platformData['platform']['name'];
                    $platformId = $platformData['platform']['id'];

                    $platform = $this->em->getRepository(Platform::class)
                        ->findOneBy(['rawgId' => $platformId]);

                    if (!$platform) {
                        $platform = new Platform();
                        $platform->setRawgId($platformId);
                        $platform->setName($platformName);
                        $this->em->persist($platform);
                    }

                    $game->addPlatform($platform);
                }

                // Ajouter les développeurs
                foreach ($gameDetails['developers'] ?? [] as $developerData) {
                    $developer = $this->em->getRepository(Developer::class)
                        ->findOneBy(['rawgId' => $developerData['id']]);
                    if (!$developer) {
                        $developer = new Developer();
                        $developer->setRawgId($developerData['id']);
                        $developer->setName($developerData['name']);
                        $this->em->persist($developer);
                    }
                    $game->addDeveloper($developer);
                }

                $this->em->persist($game);
                $output->writeln("Importé : {$game->getName()}");
                $importedCount++;
            }
            $this->em->flush();
            $this->em->clear(); // Ajoutez ceci pour libérer la mémoire Doctrine
            $output->writeln("Page $page importée.");
            $page++;
        } while (!empty($results) && $page <= $maxPages);

        $output->writeln("✅ Import terminé. Total jeux importés : $importedCount");
        return Command::SUCCESS;
    }
}

