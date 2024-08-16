<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $client = Client::create([
                'username' => 'client_' . uniqid(),
                'profile_image_url' => '/images/clients/default_profile.jpg',
                'background_image_url' => '/images/clients/default_background.jpg',
                'gender' => array_rand(['male', 'female']),
                'date_of_birth' => Carbon::parse(rand(1980, 2000) . '-' . rand(1, 12) . '-' . rand(1, 28)),
                'city' => 'City ' . $i,
            ]);

            $user = User::factory()->create([
                'first_name' => 'Client' . $i,
                'last_name' => 'User' . $i,
                'email' => 'client' . $i . '@example.com',
            ]);

            $client->user()->save($user);
        }
    }
}
