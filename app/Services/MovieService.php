<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class MovieService
{

    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
    }


    /**
     * @Desc Getting movies, numerous attempt failled so I switched to use direct Guzzle and bypass laravel's HTTP
     * Previously code didn't work either bcse the request Http not reconized or pramaters not correct
     * 
     *   $response = Http::withToken($this->apiKey)->get('https://api.themoviedb.org/3/trending/movies/day?language=en-US');
     * OR
     *   $response = Http::withHeaders([
     *      'Authorization' => 'Bearer ' . $this->apiKey,
     *      'accept' => 'application/json',
     *  ])->get('https://api.themoviedb.org/3/trending/movies/day'); *
     */
    public function getTrendingMovies($page = 1)
    {

        $client = new Client(); //new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/trending/movie/day', [
            'query' => [
                'page' => $page
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ]
        ]);

        $body = $response->getBody();
        $data = json_decode((string) $body, true);
        return $data;
        // return $movies = $data['results'];     
    }

    public function getMovieDetails($movieId)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/'. $movieId, [
            'headers' => [
              'Authorization' => 'Bearer ' . $this->apiKey,
              'accept' => 'application/json',
            ],
          ]);

        $body = $response->getBody();
        $data = json_decode((string) $body, true);
        return $data;
    }
}
