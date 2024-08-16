<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereNull('role_id')->whereNull('role_type')->take(106 6)->get();

        foreach ($users as $user) {
            $username = explode('@', $user->email)[0];

            $client = Client::create([
                'username' => $username,
                'profile_image_url' => '/images/clients/default_profile.jpg',
                'background_image_url' => '/images/clients/default_background.jpg',
                'gender' => array_rand(['male', 'female']),
                'date_of_birth' => Carbon::parse(rand(1980, 2000) . '-' . rand(1, 12) . '-' . rand(1, 28)),
                'city' => 'Client City',
            ]);

            $user->update([
                'role_id' => $client->id,
                'role_type' => Client::class,
            ]);
        }
    }
}
