<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequestMeTest extends TestCase
{
    use RefreshDatabase;
    public function test_middleware_auth_sanctum(): void
    {
        $response = $this->getJson('/api/request.me' , ['Accept' => 'application/json']);

        $response->assertStatus(401) ;
    }

    public function test_return_user_resource():void
    {
        $user = User::factory()->create() ;

        $response = $this->actingAs($user)
        ->getJson('api/request.me' , ['Accept' => 'application/json']);

        $response->assertStatus(200) ;
        $response->assertJsonStructure([
            'data' => [
                'id' ,
                'first_name' , 
                'last_name' ,
                'email' ,
                'role' 
                ]
        ]) ;
    }
}
