<?php

namespace Tests\Feature\Company\Commands;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Industry;
use App\Traits\FreeStorage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCompanyTest extends TestCase
{
    use RefreshDatabase , FreeStorage;

    private User $noRoleUser , $notVerifiedUser , $RoleUser ;
    private Industry $industry;

    public function setUp():void 
    {
        parent::setUp() ;

        $this->seed() ;

        $this->noRoleUser = User::factory()->create() ;

        $this->notVerifiedUser = User::factory()->create(['email_verified_at' => null]) ;

        $this->RoleUser = User::factory()->create() ;
        $company = Company::factory()->create(
            ['profile_image' => null ,
             'background_image' => null,
             'username' => $this->RoleUser->slug ,
            ]) ;
        $company->user()->save($this->RoleUser) ;

        $this->industry = Industry::first() ;
    }
    
    public function test_company_store_middlewares(): void
    {
        $response = $this->actingAs($this->notVerifiedUser)
                         ->postJson('api/company/store/' . $this->industry->name) ;
        $response->assertStatus(403) ;
        $response->assertJsonFragment(['message' => 'Your email address is not verified.']) ;
        

        $response = $this->actingAs($this->RoleUser)
                         ->postJson('api/company/store/' . $this->industry->name) ;
        $response->assertStatus(403) ;
        $response->assertJsonFragment(['message' => 'bruh! you already have a role']) ;


        $response = $this->actingAs($this->noRoleUser)
                         ->postJson('api/company/store/' . $this->industry->name) ;
        $response->assertStatus(422) ;
    }

    public function test_create_company_endpoint():void
    {
        var_dump(User::count());
        
        $companyData = [
            'name' => 'name' ,
            'description' => 'description' ,
            'size' => 'small' ,
            'region' => 'here' ,
            'street_address' => 'here' ,
            'city' => 'حماة' ,
        ];

        $this->assertEmpty($this->noRoleUser->role_id);
        
        $response = $this->actingAs($this->noRoleUser)
            ->postJson('api/company/store/' . $this->industry->name ,
            $companyData
        );

        $response->assertStatus(201) ;

        $company = Company::where('name' , $companyData['name'])->with('user')->first() ;

        //assert that the user gained a role
        $this->assertNotEmpty($this->noRoleUser->role_id);
        $this->assertEquals($company->user->role_id , $company->id ) ;
        $this->assertEquals($this->noRoleUser->role->id , $company->id ) ;
    }
}
