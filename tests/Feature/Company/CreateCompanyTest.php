<?php

namespace Tests\Feature\Company;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Industry;
use App\Traits\FreeStorage;
use Illuminate\Http\UploadedFile;
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

        $this->noRoleUser = User::factory()->create(['first_name' => 'no' , 'last_name' => 'Role']) ;

        $this->notVerifiedUser = User::factory()->create(['email_verified_at' => null , 'first_name' => 'not' , 'last_name' => 'verified']) ;

        $this->RoleUser = User::factory()->create(['first_name' => 'with' , 'last_name' => 'Role']) ;
        $company = Company::factory()->create(
            [
             'username' => $this->RoleUser->slug ,
            ]) ;
        $company->user()->save($this->RoleUser);

        $this->industry = Industry::first() ;
    }
    
    public function test_company_store_middlewares(): void
    {
        
        $response = $this->postJson('api/company/store') ;
        $response->assertStatus(401) ;
        $response->assertJsonFragment(['message' => 'Unauthenticated.']) ;

        $response = $this->actingAs($this->notVerifiedUser)
                         ->postJson('api/company/store') ;
        $response->assertStatus(403) ;
        $response->assertJsonFragment(['message' => 'your email address is not verified']) ;
        

        $response = $this->actingAs($this->RoleUser)
                         ->postJson('api/company/store') ;
        $response->assertStatus(403) ;
        $response->assertJsonFragment(['message' => 'bruh! you already have a role']) ;


        $response = $this->actingAs($this->noRoleUser)
                         ->postJson('api/company/store') ;
        $response->assertStatus(422) ;//here the error will be validation error
    }

    public function test_create_company_endpoint():void
    {
        
        $companyData = [
            'industry_name' => $this->industry->name ,
            'profile_image_url' => null ,
            'background_image_url' => null ,
            'name' => 'name' ,
            'description' => 'description' ,
            'size' => 'small' ,
            'region' => 'here' ,
            'street_address' => 'here' ,
            'city' => 'حماة' ,
            'gallery_image_ids' => []
        ];

        $this->assertEmpty($this->noRoleUser->role_id);
        
        $start = microtime(true);
        $response = $this->actingAs($this->noRoleUser)
        ->postJson('api/company/store' ,
            $companyData
        );
        
        $end = microtime(true);

        //var_dump($end - $start) ;

        //$this->assertLessThan(0.2 , $end - $start) ;
        //var_dump($response->json());
        $response->assertStatus(201) ;

        $response->assertJsonPath('data.username' , $this->noRoleUser->slug);
        
        $this->assertDatabaseCount('images' , 0) ;
        
        $company = Company::where('name' , $companyData['name'])
            ->with(['user' , 'gallery_images'])->first() ;

        //assert that the user gained a role
        $this->assertEquals($this->noRoleUser->slug , $company->username);
        $this->assertNotEmpty($this->noRoleUser->role_id);
        $this->assertEquals($company->user->role_id , $company->id ) ;
        $this->assertEquals($this->noRoleUser->role->id , $company->id ) ;
        $this->assertEquals($this->noRoleUser->id , $company->user->id ) ;

        $company->delete() ;
    }
}