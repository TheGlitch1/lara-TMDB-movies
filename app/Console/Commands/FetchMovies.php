<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $this->info('Fetching movies from the API...');
    
        $movieService = app(\App\Services\MovieService::class);
        $movies = $movieService->getTrendingMovies();
        foreach ($movies['results'] as $movieData) {
            // \Log::info('Movie Data:', $movieData);
            if (isset($movieData['id'])) {
                dump($movieData['title']);
                Movie::updateOrCreate(
                    ['id' => $movieData['id']],
                    [
                        'title' => $movieData['title'],
                        'overview' => $movieData['overview'],
                        'poster_path' => $movieData['poster_path'],
                        'release_date' => $movieData['release_date'] ?? null,
                        'vote_average'=>$movieData['vote_average'] ?? null,
                    ]
                );
            }
        }
        $this->info('Movies fetched and stored successfully!');
        return Command::SUCCESS;
    }
}
