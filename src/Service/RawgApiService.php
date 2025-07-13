<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RawgApiService
{
    private string $apiUrl = 'https://api.rawg.io/api';
    private string $apiKey; // à configurer

    public function __construct(
        private HttpClientInterface $client,
        string $rawgApiKey
    ) {
        $this->apiKey = $rawgApiKey;
    }

    public function fetchGames(int $page = 1, int $pageSize = 60): array
    {
        $response = $this->client->request('GET', $this->apiUrl.'/games', [
            'query' => [
                'key' => $this->apiKey,
                'page' => $page,
                'page_size' => $pageSize
            ]
        ]);

        return $response->toArray();
    }

    public function fetchGameDetails(int $rawgId): array
    {
        $response = $this->client->request('GET', $this->apiUrl."/games/{$rawgId}", [
            'query' => [
                'key' => $this->apiKey
            ]
        ]);

        return $response->toArray();
    }
}
