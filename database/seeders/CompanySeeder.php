<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Industry;
use App\Constants\SyrianCities;
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
                'profile_image_url' => null ,
                'background_image_url' => null ,
                'description' => 'Description for company ' . $user->first_name,
                'size' => rand(10, 100) . ' employees',
                'city' => fake()->randomElement(SyrianCities::$allCities),
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
