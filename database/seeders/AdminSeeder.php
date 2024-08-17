<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminUsers = User::whereNull('role_id')->whereNull('role_type')->take(6)->get();

        foreach ($adminUsers as $adminData) {
            $user = User::updateOrCreate([
                'email' => $adminData['email'],
            ], [
                'first_name' => $adminData['first_name'],
                'last_name' => $adminData['last_name'],
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
            ]);

            if (!$user->role_id && !$user->role_type) {
                $admin = Admin::create([]);
                $admin->user()->save($user);

                $user->update([
                    'role_id' => $admin->id,
                    'role_type' => Admin::class,
                ]);
            }
        }
    }
}
