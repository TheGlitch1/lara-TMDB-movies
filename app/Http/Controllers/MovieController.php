<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Services\MovieService;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function showTrending(Request $request)
    {
        $page = $request->get('page', 1);
        $period = $request->get('period', "day");
        $data = $this->movieService->getTrendingMovies($page, $period);
        if (!$data) {
            return redirect()->route('movies.trending')->withErrors('The requested page is out of range or period, period diffrent than day/week or an error occurred.');
        }
        $movies = $data->results;
        $viewType = 'trending';
        
        $total = $data->total_results-100;
        $perPage = 20; 
        $movies = new LengthAwarePaginator($movies, $total, $perPage, $page, ['path' => route('movies.trending')]);
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


    public function showDetails($movieId)
    {
        $movie = $this->movieService->getMovieDetails($movieId);
        return view('movies.details', compact('movie'));
    }



    /**
     * @Desc testing get movies. issue with gazzel or api.
     */
    public function showMovies()
    {
        $response = Http::withToken(config('services.tmdb.key'))
            ->get('https://api.themoviedb.org/3/trending/movies/day');

        $movies = $this->movieService->getAllTrendingMovies();
        return response()->json($movies);
        dd($response->json());
    }
}
