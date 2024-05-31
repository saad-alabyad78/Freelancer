<?php

namespace Tests\Feature\Freelancer\Commands;


use Tests\TestCase;
use App\Models\User;
use App\Constants\Gender;
use App\Models\Freelancer;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFreelancerTest extends TestCase
{
    use RefreshDatabase ;
    private User $user ;

    public function setUp(): void
    {
        parent::setUp() ;
        $this->seed() ;
        $this->user = User::factory()->create() ;
    }
    public function test_create_freelancer(): void
    {
        $data = [
            'profile_image' => null,
            'background_image' => UploadedFile::fake()->image('back.png' , 200,200)->size(2000),
            'headline' => 'hi hi hi hi hi hi hi hi hi',
            'description' => 'hi hi h i hi h ih  dsk fkfksdfkj kjk lsdklfjkdsf lkdsf kflsd fkldsjf sdjf lsjdfk js',
            'city' => 'دمشق',
            'gender' => Gender::FEMALE,
            'date_of_birth' => '2002-01-01',
            'job_role_id' => 1,
            'skills' => ['vue','c++','php','html','css','mysql'] ,
        ] ;
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/freelancer/store' , $data , ['Accept'=>'application/json'] );
        
        
        $response->assertStatus(201);

        $freelancer = Freelancer::first() ;

        $this->assertCount(6 , $freelancer->skills()->get()) ;

        $freelancer->delete() ;
    }
}
