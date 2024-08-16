<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Industry;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $industry = Industry::inRandomOrder()->first();

            $company = Company::create([
                'name' => 'Company ' . $i,
                'lat' => rand(35, 45),
                'lon' => rand(25, 35),
                'profile_image_url' => '/images/companies/default_profile.png',
                'background_image_url' => '/images/companies/default_background.png',
                'username' => 'company_' . uniqid(),
                'description' => 'Description for company ' . $i,
                'size' => rand(10, 100) . ' employees',
                'city' => 'City ' . $i,
                'region' => 'Region ' . $i,
                'street_address' => 'Street Address ' . $i,
                'industry_name' => $industry->name,
            ]);

            $user = User::factory()->create([
                'first_name' => 'Company' . $i,
                'last_name' => 'User' . $i,
                'email' => 'company' . $i . '@example.com',
            ]);

            $company->user()->save($user);
        }
    }
}
