<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class YoutubeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_connection()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_search()
    {
        $response = $this->withHeaders([
            'search' => 'a',
        ])->get('/');
        $response->assertStatus(200);
    }

    public function test_search_empty()
    {
        $response = $this->withHeaders([
            'search' => '',
        ])->get('/');
        $response->assertStatus(200);
    }

}
