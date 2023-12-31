<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route::get('/movies', [MovieController::class, 'showMovies']); // Route made to test directly in controller before woring with service layer.
Route::get('/test-exception', function () {
    throw new \App\Exceptions\MovieApiException('This is a test.', 400);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
    ])->group(function () {
        Route::get('/movies', [MovieController::class, 'allMovies'])->middleware('check.movies.exist')->name('movies.all');
        Route::get('/movies/trending', [MovieController::class, 'showTrending'])->name('movies.trending');
        Route::get('/movies/{movieId}', [MovieController::class, 'showDetails'])->name('movie.details');
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
});
