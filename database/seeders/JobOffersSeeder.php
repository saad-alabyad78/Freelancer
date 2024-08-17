<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Database\Seeder;

class JobOffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->makeOne([
            'first_name' => 'company1',
            'last_name' => null,
            'email' => 'company1@gmail.com',
        ]);

        $company = Company::factory()->createOne();
        $company->user()->save($user);

        JobOffer::factory()
            ->count(50)
            ->for($company)
            ->create();
    }
}
