<?php

namespace Tests\Feature\Company\Commands;

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
        $this->contact_links = ContactLink::factory(5)->create([
            'company_id' => $this->company->id 
        ]);
           
        $response = $this->putJson('api/company' , [
            'region' => 'new region' ,
            'contact_links' => ['hiihihihih'] 
        ] , ['Accept'=>'application/json']) ;

        $response->assertStatus(200) ;
        $response->assertJsonCount(1 , 'data.contact_links') ;
        $this->assertDatabaseCount('contact_links' , 1) ;
    }
}