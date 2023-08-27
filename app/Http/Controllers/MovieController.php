<?php

namespace App\Http\Controllers;

use App\Models\Movie;
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
        $period = $request->get('period',"day");
        $data = $this->movieService->getTrendingMovies($page,$period);
        $movies = $data->results;
        // dd($movies);
        $viewType = 'trending';
        return view('movies.index', compact('movies', 'viewType'));
    }
    
    /**
     * @Desc get movies from dataBase
     */
    public function allMovies() 
    {
        // Fetch movies from the database
        $movies = Movie::paginate(20);
        $viewType = 'all';
        return view('movies.index', compact('movies', 'viewType'));
    }


    public function showDetails($movieId) {
        $movie = $this->movieService->getMovieDetails($movieId);
        // dd($movieDetails);
        // return response()->json($movieDetails);
        return view('movies.details', compact('movie'));
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
