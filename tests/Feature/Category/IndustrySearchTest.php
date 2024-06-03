<?php

namespace Tests\Feature\Category;

use Tests\TestCase;
use App\Models\Industry;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndustrySearchTest extends TestCase
{
    use RefreshDatabase ;
    /**
     * A basic feature test example.
     */
    public function test_all_industry_will_be_returned(): void
    {
        Industry::create(['name' => 'i1']);
        Industry::create(['name' => 'i2']);
        Industry::create(['name' => 'i3']);
        Industry::create(['name' => 'i4']);

        $response = $this->postJson('api/category/industry/search' , []);

        $this->assertDatabaseCount('industries' , 4) ;

        $response->assertJsonCount(4 , 'data') ;

        $response->assertStatus(200);
    }
}
