<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class NoRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        for ($i = 0; $i < 50; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = strtolower($firstName . '.' . $lastName . $i . '@example.com');
            $verifiedAt = $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d H:i:s');

            User::updateOrCreate([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'email_verified_at' => $verifiedAt,
            ], [
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}
