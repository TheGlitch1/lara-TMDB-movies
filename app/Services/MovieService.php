<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Exceptions\MovieApiException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class MovieService
{
    protected Client $client;
    protected string $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('services.tmdb.key');
    }

    protected function sendRequest(string $url, array $queryParams = []): object
    {
        try {
            $response = $this->client->request('GET', $url, [
                'query' => $queryParams,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'accept' => 'application/json',
                ],
            ]);

            $body = $response->getBody();
            return json_decode((string) $body);
        } catch (ClientException | RequestException $e) {
            $this->handleException($e);
        }
    }

    protected function handleException($e): void
    {
        $statusCode = $e->getResponse()->getStatusCode();
        if ($statusCode === 400) {
            throw new MovieApiException("Pagination out of range or period not ok", MovieApiException::PAGINATION_OUT_OF_RANGE);
        } else {
            throw new MovieApiException("The MovieDB API is down", MovieApiException::API_DOWN);
        }
    }

    public function getTrendingMovies(int $page = 1, string $period = "day"): ?object
    {
        return $this->sendRequest("https://api.themoviedb.org/3/trending/movie/{$period}", ['page' => $page]);
    }

    public function getMovieDetails(int $movieId): object|array
    {
        return $this->sendRequest("https://api.themoviedb.org/3/movie/{$movieId}");
    }

    public function getAllTrendingMovies(): array
    {
        $page = 1;
        $hasMorePages = true;
        $allMovies = [];

        while ($hasMorePages) {
            $data = $this->sendRequest('https://api.themoviedb.org/3/trending/movie/day', ['page' => $page]);

            if (empty($data->results) || $page > 10) { 
                $hasMorePages = false;
                continue;
            }

            $allMovies = array_merge($allMovies, $data->results);
            $page++;
        }

        return $allMovies;
    }
}
    