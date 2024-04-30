<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->actingAs($user)->post('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logout successful'
            ]);

        $this->assertEmpty($user->tokens);
    }
}
