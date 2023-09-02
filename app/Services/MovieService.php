<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Exceptions\MovieApiException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

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
     * @param int $page The page number.
     * @param string $period The period ("day" or "week").
     * @return object|null $data->results $data->pages $data->....
     */
    public function getTrendingMovies($page = 1, $period = "day"): ?object
    {
        $client = new Client(); //new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', 'https://api.themoviedb.org/3/trending/movie/' . $period, [
                'query' => [
                    'page' => $page
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'accept' => 'application/json',
                ],
                // 'http_errors' => false
            ]);
            
            $body = $response->getBody();
            $data = json_decode((string) $body);
            return $data;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 400) {
                throw new MovieApiException("Pagination out of range or period not ok", MovieApiException::PAGINATION_OUT_OF_RANGE);
            } else {
                throw new MovieApiException("The MovieDB API is down", MovieApiException::API_DOWN);
            }
        }
        
    }

    public function getMovieDetails($movieId)
    {   
        try{

            $client = new Client();
            $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $movieId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'accept' => 'application/json',
                ],
            ]);
            
            $body = $response->getBody();
            $data = json_decode((string) $body);
            return $data;
        }
        catch(\Exception $e) {
            throw new MovieApiException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Desc added this function in case we want to handle pagination also to retrive all the movies 
     * This method should be called by the command
     * @Exceptions : here I tried another way to handle the exception and how i send message data. I hope you will notice guys.
     * @return Array movies
     */
    public function getAllTrendingMovies()
    {   
        try{
            
            $client = new Client(); //new \GuzzleHttp\Client();
            
            $page = 1;
            $hasMorePages = true;
            $allMovies = [];
            
            while ($hasMorePages) {
                $response = $client->request('GET', 'https://api.themoviedb.org/3/trending/movie/day', [
                'query' => [
                    'page' => $page
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'accept' => 'application/json',
                    ]
                ]);
                if ($response->getStatusCode() != 200) {
                    if ($response->getStatusCode() == 404) {
                        throw new MovieApiException("Pagination out of range", MovieApiException::PAGINATION_OUT_OF_RANGE);
                    } else {
                        throw new MovieApiException("The MovieDB API is down", MovieApiException::API_DOWN);
                    }
                }
                $body = $response->getBody();
                $movies = json_decode((string) $body);
                //Limmiting the API to fetch only 10 pages in case you want to store everything just remove the condition on page
                if (empty($movies->results) || $page > 10) { 
                    $hasMorePages = false;
                    continue;
                }
                
                $allMovies = array_merge($allMovies, $movies->results);
                
                $page++;
            }

            return $allMovies;
        }
        catch (RequestException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(), true);
            $status_code = $responseBody['status_code'];
            $status_message = $responseBody['status_message'];
            $message = "Status Code: $status_code, Message: $status_message";
            if ($e->getResponse()->getStatusCode() === 400) {
                throw new MovieApiException($message, MovieApiException::PAGINATION_OUT_OF_RANGE);
            } else {
                throw new MovieApiException($message, MovieApiException::API_DOWN);
            }
        }
    }
        
}
    