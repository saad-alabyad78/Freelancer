<?php

namespace Tests\Feature\Category;

use Tests\TestCase;
use App\Models\JobRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobRolesSearchTest extends TestCase
{
    use RefreshDatabase ;
    
    public function test_job_role_search(): void
    {
        JobRole::create(['name' => 's1']) ;
        JobRole::create(['name' => 's2']) ;
        JobRole::create(['name' => 's3']) ;

        $this->assertDatabaseCount('job_roles' , 3) ;

        $response = $this->postJson('api/category/job_role/search' , ['name' => '']) ;
        $response->assertStatus(200) ;


        $response = $this->postJson('api/category/job_role/search' , ['name' => 's']) ;
        $response->assertJsonCount(3 , 'data') ;
        $response->assertStatus(200);

        $response = $this->postJson('api/category/job_role/search' , ['name' => '1']) ;
        $response->assertJsonCount(1 , 'data') ;
        $response->assertStatus(200);
    }
}
