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
        $this->withMiddleware([StartSession::class]);  // Add StartSession middleware
    }
    /**
     * A basic test example.
     *
     * @return void
     */
     /** @test */
    public function it_shows_trending_movies_for_day_period()
    {   
        $this->withoutDeprecationHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('movies.trending'))->assertStatus(200);
    }

     /** @test */
    public function it_shows_trending_movies_for_week_period()
    {
        // $this->withoutDeprecationHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/movies/trending?period=week')->assertStatus(200);
    }
    
     /** @test */
    public function it_validates_period_parameter()
    {
        $this->withoutDeprecationHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->get('/movies/trending?period=invalid')
        ->assertRedirect(route('movies.trending'))
        ->assertStatus(303)
        ->assertSessionHasErrors();

    }
}
