<?php

namespace Tests\Feature\Client;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Constants\Gender;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateClientTest extends TestCase
{
    use RefreshDatabase ;   
    private User $user ;

    public function setUp(): void
    {
        parent::setUp() ;
        
        $this->user = User::factory()->create() ;
        
    }
    public function test_create_client(): void
    {
        $this->assertDatabaseCount('clients' , 0) ;

        $data = [
            'gender' => Gender::MALE ,
            'date_of_birth' => Carbon::now()->subYears(16)->toDateString() ,
            'city' => 'دمشق'
        ] ;


        $response = $this->actingAs($this->user)
        ->postJson('/api/client/store' , $data , ['Accept' => 'application/json']);

        //var_dump($response->json()) ;

        $response->assertStatus(201);

        $this->assertDatabaseCount('clients' , 1) ;

        $client = Client::first() ;

        $this->assertEquals($client->date_of_birth , $data['date_of_birth']) ;
        $this->assertEquals($client->city , $data['city']) ;

        $client->delete() ;
    }
}
