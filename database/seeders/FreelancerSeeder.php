<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Freelancer;
use App\Models\JobRole;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FreelancerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $jobRole = JobRole::inRandomOrder()->first();

            $freelancer = Freelancer::create([
                'username' => 'freelancer_' . uniqid(),
                'profile_image_url' => '/images/freelancers/default_profile.jpg',
                'background_image_url' => '/images/freelancers/default_background.jpg',
                'headline' => 'Freelancer ' . $i,
                'description' => 'This is a description for freelancer ' . $i,
                'city' => 'City ' . $i,
                'gender' => array_rand(['male', 'female']),
                'date_of_birth' => Carbon::parse(rand(1980, 2000) . '-' . rand(1, 12) . '-' . rand(1, 28)),
                'job_role_id' => $jobRole->id,
            ]);

            $user = User::factory()->create([
                'first_name' => 'Freelancer' . $i,
                'last_name' => 'User' . $i,
                'email' => 'freelancer' . $i . '@example.com',
            ]);

            $freelancer->user()->save($user);
        }
    }
}
