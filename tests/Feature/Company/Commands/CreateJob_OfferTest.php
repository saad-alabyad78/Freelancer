<?php

namespace Tests\Feature\Company\Commands;

use Tests\TestCase;
use App\Models\User;
use App\Models\Skill;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Industry;
use App\Constants\Job_OfferTypes;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateJob_OfferTest extends TestCase
{
    use RefreshDatabase ;
    private Company $company ;
    private User $user ;
    private Industry $industry ;
    public function setUp(): void
    {
        parent::setUp() ;

        $this->seed() ;

        $this->industry = Industry::inRandomOrder()->take(1)->first();
        
        $this->user = User::factory()->create() ;

        $this->company = Company::factory()->create([
            'profile_image' => null,
            'background_image' => null,
            'username' => $this->user->slug ,
        ]) ;

        $this->company->user()->save($this->user) ;
    }

    public function test_create_job_offer(): void
    {
        $data = [
            'type' => Job_OfferTypes::FULL_TIME,
            'max_salary' => 1000,
            'min_salary' => 10,
            // 'max_age' => 30,
            // 'min_age' => 18,
            'description' =>  'hi there i am using whatsapp ,! haaaaaaaaaaa ',
            'transportation' =>  false,
            'health_insurance' =>  false ,
            'military_service' =>  true,
            'gender' =>  null,
            
            'job_role' =>  JobRole::inRandomOrder()->take(1)->first()->name,
            'skills' =>  Skill::inRandomOrder()->take(5)->pluck('name' , 'name'),
        ] ;
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/company/' . $this->company->username . '/job_offer/store/' . $this->industry->name ,
            $data) ;

        
        var_dump($response->json()); 
        
        $response->assertStatus(201) ;
            
    }
}
