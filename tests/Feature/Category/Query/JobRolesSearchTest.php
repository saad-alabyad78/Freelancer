<?php

namespace Tests\Feature\Category\Query;

use Tests\TestCase;
use App\Models\JobRole;
use Illuminate\Foundation\Testing\WithFaker;
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

        $response = $this->postJson('api/category/job_roles/search' , ['name' => '']) ;
        $response->assertStatus(422) ;


        $response = $this->postJson('api/category/job_roles/search' , ['name' => 's']) ;
        $response->assertJsonCount(3 , 'data') ;
        $response->assertStatus(200);
        $response->assertHeader('Accept' , 'application/json');

        $response = $this->postJson('api/category/job_roles/search' , ['name' => '1']) ;
        $response->assertJsonCount(1 , 'data') ;
        $response->assertStatus(200);
        $response->assertHeader('Accept' , 'application/json');
    }
}
