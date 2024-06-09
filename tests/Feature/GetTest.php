<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->getJson('/api/test')
        ->withHeaders(
            [
                'Content-Type'=>'application/json' ,
                'Accept' => 'application/json'
            ]);

        var_dump($response->json()) ;
    }
}
