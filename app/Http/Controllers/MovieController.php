<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\MovieService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function showTrending(Request $request): View
    {
            
            $page = $request->get('page', 1);
            $period = $request->get('period', "day");
            $data = $this->movieService->getTrendingMovies((int) $page,(string) $period);
           
            $movies = $data->results;
            $viewType = 'trending';
            
            $total = $data->total_results;
            $perPage = 20; 
            $movies = new LengthAwarePaginator($movies, $total, $perPage, $page, ['path' => route('movies.trending')]);
            $movies->appends(['period' => $period]);
            return view('movies.index', compact('movies', 'viewType'));
        
    }

    /**
     * @Desc get movies from dataBase
     */
    public function allMovies(Request $request): View|RedirectResponse
    {
        try {
            $request->validate([
                'page' => 'integer|min:1',
                'filter' => 'nullable|in:most_voted,least_voted,under_5,all',
            ]);
            $filter = $request->get('filter', 'all');
            $movies = $this->filterMovies($filter);
            if ($request->input('page', 1)> $movies->lastPage()) {
                throw ValidationException::withMessages([
                    'page' => ["The requested page number is out of range."]
                ]);
            }
            $viewType = 'all';
            return view('movies.index', compact('movies', 'viewType'));
        } catch (ValidationException $e) {
            return redirect()->route('movies.all')->withErrors("Validation failed: " . $e->getMessage());
        }
    }

    private function filterMovies(string $filter)
    {
        $query = Movie::query();

        match ($filter) {
            'most_voted' => $query->mostVoted(),
            'least_voted' => $query->leastVoted(),
            default => null,
        };

        return $query->paginate(20)->appends(['filter' => $filter]);
    }


    public function showDetails(int $movieId): View
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
