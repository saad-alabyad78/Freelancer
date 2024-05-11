<?php

namespace Tests\Feature\Category\Query;

use Tests\TestCase;
use App\Models\Skill;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkillSearchTest extends TestCase
{
    use RefreshDatabase ;
    
    public function test_skills_search(): void
    {
        Skill::create(['name' => 's1']) ;
        Skill::create(['name' => 's2']) ;
        Skill::create(['name' => 's3']) ;

        $this->assertDatabaseCount('skills' , 3) ;

        $response = $this->postJson('api/category/skills/search' , ['name' => '']) ;
        $response->assertStatus(422) ;


        $response = $this->postJson('api/category/skills/search' , ['name' => 's']) ;
        $response->assertJsonCount(3 , 'data') ;
        $response->assertStatus(200);
        $response->assertHeader('Accept' , 'application/json');

        $response = $this->postJson('api/category/skills/search' , ['name' => '1']) ;
        $response->assertJsonCount(1 , 'data') ;
        $response->assertStatus(200);
        $response->assertHeader('Accept' , 'application/json');
    }
}
