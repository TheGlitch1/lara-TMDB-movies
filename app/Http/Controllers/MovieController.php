<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService) {
        $this->movieService = $movieService;
    }

    
    public function showTrending(Request $request) {
        $page = $request->get('page', 1);
        $movies = $this->movieService->getTrendingMovies($page);
        return response()->json($movies);
        // return view('movies.trending', compact('movies'));
    }
    
    public function showDetails($movieId) {
        $movieDetails = $this->movieService->getMovieDetails($movieId);
        return response()->json($movieDetails);

        // return view('movies.details', compact('movieDetails'));
    }
    
    /**
     * @Desc testing get movies. issue with gazzel or api.
     */
    public function showMovies(){
        $response = Http::withToken(config('services.tmdb.key'))
        ->get('https://api.themoviedb.org/3/trending/movies/day');
        
        $movies = $this->movieService->getAllTrendingMovies();
        return response()->json($movies);
        dd($response->json());
    }
}
