<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\CheckMoviesExist;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieTrendingTest extends TestCase
{   

    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutDeprecationHandling();
        $this->withMiddleware([StartSession::class]);  // Add StartSession middleware
        $user = User::factory()->create();
        $this->actingAs($user);
    }
    /**
     * A basic test example.
     *
     * @return void
     */
     /** @test */
    public function it_shows_trending_movies_for_day_period(): void
    {   
       $this->get(route('movies.trending'))->assertStatus(200);
    }

     /** @test */
    public function it_shows_trending_movies_for_week_period(): void
    {
        $this->get('/movies/trending?period=week')->assertStatus(200);
    }
    
     /** @test */
    public function it_validates_period_parameter(): void
    {
        
        $this->get('/movies/trending?period=invalid')
        ->assertRedirect(route('movies.trending'))
        ->assertStatus(303)
        ->assertSessionHasErrors();

    }
}
