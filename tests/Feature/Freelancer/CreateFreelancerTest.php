<?php

namespace Tests\Feature\Freelancer;


use Tests\TestCase;
use App\Models\User;
use App\Constants\Gender;
use App\Models\Skillable;
use App\Models\Freelancer;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\Freelancer\FreelancerResource;

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
            'skill_ids' => ['1','2', '3','4','5','6'] ,
        ] ;
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/freelancer/store' , $data , ['Accept'=>'application/json'] );
       
        $response->assertStatus(201);

       
        $freelancer = Freelancer::where('date_of_birth' , '2002-01-01')->first() ;

        $this->assertCount(6 , $freelancer->skills()->get()) ;

        $freelancer->delete() ;

    }
}
