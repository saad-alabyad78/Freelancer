<?php

namespace Tests\Feature\Company;

use Tests\TestCase;
use App\Models\User;
use App\Models\Skill;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Industry;
use App\Models\JobOffer;
use App\Constants\LocationType;
use App\Constants\AttendanceType;
use App\Constants\Job_OfferTypes;
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
            'username' => $this->user->slug ,
        ]) ;

        $this->company->user()->save($this->user) ;
    }

    public function test_create_job_offer(): void
    {
        $data = [
            
            'industry_name' => $this->industry->name,
            'job_role_id' =>  JobRole::inRandomOrder()->take(1)->first()->id,
            
            'attendance_type' => AttendanceType::FULL_TIME ,
            'location_type' => LocationType::ON_SITE ,
            'max_salary' => 1000,
            'min_salary' => 10,
            // 'max_age' => 30,
            // 'min_age' => 18,
            'description' =>  'hi there i am using whatsapp ,! haaaaaaaaaaa ',
            'transportation' =>  false,
            'health_insurance' =>  false ,
            'military_service' =>  true,
            'gender' =>  null,
            
            'skills' => [
                    ['id'=>1 , 'required'=>true] ,
                    ['id'=>2 , 'required'=>true] ,
                    ['id'=>3 , 'required'=>false] ,
                    ['id'=>4 , 'required'=>true] ,
                    ['id'=>5 , 'required'=>true] ,
                ],
        ] ;
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/company/job_offer/store' ,
            $data) ;
        
        $response->assertStatus(201) ;

        $offer = JobOffer::where('description' ,  'hi there i am using whatsapp ,! haaaaaaaaaaa')->first() ;

        $this->assertDatabaseCount('job_offers' , 31) ;
        $this->assertEquals($offer->skills()->count() , 5) ;
    }
}
