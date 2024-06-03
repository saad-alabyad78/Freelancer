<?php

namespace Tests\Feature\Category;

use Tests\TestCase;
use App\Models\Skill;
use App\Models\JobRole;
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

        $response = $this->postJson('api/category/skill/search' , []) ;
        
        $response->assertJsonCount(3 , 'data') ;

        $response = $this->postJson('api/category/skill/search' , ['name' => 's']) ;
        $response->assertJsonCount(3 , 'data') ;
        $response->assertStatus(200);

        $response = $this->postJson('api/category/skill/search' , ['name' => '1']) ;
        $response->assertJsonCount(1 , 'data') ;
        $response->assertStatus(200);
    }

    public function test_skills_by_job_role()
    {
        $s1 = Skill::create(['name' => 's1']) ;
        $s2 = Skill::create(['name' => 's2']) ;
        $s3 = Skill::create(['name' => 's3']) ;
        
        $r1 = JobRole::create(['name' => 'r1']);
        $r2 = JobRole::create(['name' => 'r2']);
        $r3 = JobRole::create(['name' => 'r3']);

        $r1->skills()->saveMany([$s1 , $s2  , $s3]) ;
        $r2->skills()->saveMany([$s1 , $s2 ]) ;

        $response = $this->postJson('api/category/skill/search' , [
            'job_role_id' => 'invalid role ' ,
        ]) ;
        var_dump($response->json());
        $response->assertStatus(422);

        $response = $this->postJson('api/category/skill/search' , [
            'name' => 's1' ,
            'job_role_id' => 3 ,
        ]) ;
        $response->assertJsonCount(0 , 'data') ;

        $response = $this->postJson('api/category/skill/search' , [
            'job_role_id' => 1 ,
        ]) ;
        $response->assertJsonCount(3 , 'data') ;

        $response = $this->postJson('api/category/skill/search' , [
            'name' => '1' ,
            'job_role_id' => 1 ,
        ]) ;
        $response->assertJsonCount(1 , 'data') ;
        
        $response->assertJsonPath('data.0.name' , 's1') ;

    }
}
