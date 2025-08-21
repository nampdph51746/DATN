<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_movie()
{
    $response = $this->post('/admin/movies', [
        'name' => '',
        'duration_minutes' => 90,
        'release_date' => now()->format('Y-m-d'),
        'language' => 'VN',
        'status' => 'showing',
        'country_id' => 1,
        'age_limit_id' => 1,
        'genre_ids' => [1]
    ]);

    $response->assertStatus(302); // redirect thành công
    $this->assertDatabaseHas('movies', ['name' => 'Phim test']);
}
}
