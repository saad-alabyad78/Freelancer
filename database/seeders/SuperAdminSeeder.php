<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminUsers = User::whereNull('role_id')->whereNull('role_type')->take(4)->get();

        foreach ($superAdminUsers as $superAdminData) {
            $user = User::updateOrCreate([
                'email' => $superAdminData['email'],
            ], [
                'first_name' => $superAdminData['first_name'],
                'last_name' => $superAdminData['last_name'],
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
            ]);

            if (!$user->role_id && !$user->role_type) {
                $superAdmin = SuperAdmin::create([]);
                $superAdmin->user()->save($user);

                $user->update([
                    'role_id' => $superAdmin->id,
                    'role_type' => SuperAdmin::class,
                ]);
            }
        }
    }
}
