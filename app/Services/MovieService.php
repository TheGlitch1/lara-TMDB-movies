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
     * 
     * @return Array $data['results'] $data['pages'] $data[....]
     */
    public function getTrendingMovies($page = 1,$period="day")
    {

        $client = new Client(); //new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/trending/movie/'. $period, [
            'query' => [
                'page' => $page
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ]
        ]);

        $body = $response->getBody();
        $data = json_decode((string) $body);
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
        $data = json_decode((string) $body);
        return $data;
    }

    /**
     * @Desc added this function in case we want to handle pagination also to retrive all the movies 
     * This method should be called by command
     * @return Array movies
     */
    public function getAllTrendingMovies()
    {

        $client = new Client(); //new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/trending/movie/day', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ]
        ]);

        $body = $response->getBody();
        $data = json_decode((string) $body, true);
        
        return  $data['results'];    
    }
}
