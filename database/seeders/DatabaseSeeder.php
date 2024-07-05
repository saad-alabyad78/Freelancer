<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //SuperAdminSeeder::class ,
            AdminSeeder::class ,
            NoRoleUserSeeder::class ,
            IndustrySeeder::class ,
            SkillSeeder::class ,
            Job_RoleSeeder::class ,
            FreelancerSeeder::class ,
            JobOffersSeeder::class ,
        ]);

    }
}
