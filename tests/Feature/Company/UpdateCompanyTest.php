<?php

namespace Tests\Feature\Company;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\ContactLink;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCompanyTest extends TestCase
{
    use RefreshDatabase ;
    private User $user ;
    private Company $company ;
    private  $contact_links ;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed() ;
        
        
        $this->user = User::factory()->create() ;
       
        $this->company = Company::factory()->create(['username' => $this->user->slug]) ;
        $this->company->user()->save($this->user) ;

       

        $this->actingAs($this->user) ;
    }
   
    public function test_update_company(): void
    {
           
        $response = $this->putJson('api/company' , [
            'region' => 'new region' 
        ] , ['Accept'=>'application/json']) ;

        
        $response->assertStatus(200) ;
    }
}