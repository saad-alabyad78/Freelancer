<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Industry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereNull('role_id')->whereNull('role_type')->take(15)->get();

        $industries = Industry::pluck('name')->toArray();

        foreach ($users as $user) {
            $username = explode('@', $user->email)[0];

            $industryName = $industries[array_rand($industries)];

            $company = Company::create([
                'username' => $username,
                'name' => 'Company ' . $user->first_name,
                'lat' => rand(35, 45),
                'lon' => rand(25, 35),
                'profile_image_url' => '/images/companies/default_profile.jpg',
                'background_image_url' => '/images/companies/default_background.jpg',
                'description' => 'Description for company ' . $user->first_name,
                'size' => rand(10, 100) . ' employees',
                'city' => 'Company City',
                'region' => 'Region ' . rand(1, 10),
                'street_address' => 'Street Address ' . rand(1, 100),
                'industry_name' => $industryName,
            ]);

            $user->update([
                'role_id' => $company->id,
                'role_type' => Company::class,
            ]);
        }
    }
}
