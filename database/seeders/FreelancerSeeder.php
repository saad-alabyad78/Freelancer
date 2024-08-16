<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Freelancer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FreelancerSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereNull('role_id')->whereNull('role_type')->take(15)->get();

        foreach ($users as $user) {
            $username = explode('@', $user->email)[0];

            $freelancer = Freelancer::create([
                'username' => $username,
                'profile_image_url' => '/images/freelancers/default_profile.jpg',
                'background_image_url' => '/images/freelancers/default_background.jpg',
                'headline' => 'Freelancer ' . $user->first_name,
                'description' => 'This is a description for freelancer ' . $user->first_name,
                'city' => 'Freelancer City',
                'gender' => array_rand(['male', 'female']),
                'date_of_birth' => Carbon::parse(rand(1980, 2000) . '-' . rand(1, 12) . '-' . rand(1, 28)),
                'job_role_id' => rand(1, 5),
            ]);

            $user->update([
                'role_id' => $freelancer->id,
                'role_type' => Freelancer::class,
            ]);
        }
    }
}
