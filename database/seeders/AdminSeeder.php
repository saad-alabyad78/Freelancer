<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            User::updateOrCreate([
                'email' => 'admin1@gmail.com',
            ], [
                'first_name' => 'admin1',
                'last_name' => 'admin',
                'email_verified_at' => '2000-11-11',
                'password' => Hash::make('12345678'),
            ]),
            User::updateOrCreate([
                'email' => 'admin2@gmail.com',
            ], [
                'first_name' => 'admin2',
                'last_name' => 'admin',
                'email_verified_at' => '2000-11-11',
                'password' => Hash::make('12345678'),
            ]),
            User::updateOrCreate([
                'email' => 'admin3@gmail.com',
            ], [
                'first_name' => 'admin3',
                'last_name' => 'admin',
                'email_verified_at' => '2000-11-11',
                'password' => Hash::make('12345678'),
            ]),
            User::updateOrCreate([
                'email' => 'admin4@gmail.com',
            ], [
                'first_name' => 'admin4',
                'last_name' => 'admin',
                'email_verified_at' => '2000-11-11',
                'password' => Hash::make('12345678'),
            ]),
        ];

        foreach ($users as $user) {
            if (!$user->role_id) {
                $admin = Admin::create();
                $admin->user()->save($user);
            }
        }
    }
}
