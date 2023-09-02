<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Console\Command;
use App\Exceptions\MovieApiException;

class FetchMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch movies from the API and store them in the database';

    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        parent::__construct();
        $this->movieService = $movieService;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            
            
            $allMovies = $this->movieService->getAllTrendingMovies();
            
            $this->call('delete:movies');
            
            $this->info('Fetching movies from the API...');
            if (empty($allMovies)) $this->info('Fetching movies NO movies returned...');
            else $this->info('Fetching movies MOVIES FOUND returned...');
            foreach ($allMovies as $movieData) {
                if (isset($movieData->id)) {
                    Movie::updateOrCreate(
                        ['oid' => $movieData->id],
                        [
                            'oid' => $movieData->id,
                            'title' => $movieData->title,
                            'overview' => $movieData->overview,
                            'poster_path' => $movieData->poster_path,
                            'release_date' => $movieData->release_date ?? null,
                            'vote_average' => $movieData->vote_average ?? null,
                        ]
                    );
                }
            }

            $this->info("Movies fetched and stored successfully!");
        } catch (MovieApiException $e) {
            $this->error($e->getMessage());
        }
    }
}
