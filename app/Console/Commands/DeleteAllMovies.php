<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;

class DeleteAllMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all movies from the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        if (Movie::count() === 0) {
            $this->info('No movies in the database to delete.');
            return;
        }

        Movie::truncate();
        $this->info('All movies have been deleted.');
        return Command::SUCCESS;
    }
}
