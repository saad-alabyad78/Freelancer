<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            User::updateOrCreate([
                'email' => 'saadalabyad78@gmail.com',
            ], [
                'first_name' => 'saad',
                'last_name' => 'alabyad',
                'email_verified_at' => '2000-11-11',
                'password' => Hash::make('12345678'),
            ]),
            User::updateOrCreate([
                'email' => 'yasserjamalaldeen@gmail.com',
            ], [
                'first_name' => 'sham',
                'last_name' => 'jamous',
                'email_verified_at' => '2000-11-11',
                'password' => Hash::make('12345678'),
            ]),
        ];

        foreach ($users as $user) {
            if (!$user->role_id) {
                $superAdmin = SuperAdmin::create();
                $superAdmin->user()->save($user);
            }
        }
    }
}
