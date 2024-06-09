<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\JobRole;
use App\Constants\Gender;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Freelancer>
 */
class FreelancerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_image_url' => null ,
            'background_image_url' => null ,
            'profile_image_id' => null ,
            'background_image_id' => null ,
            'headline' => fake()->sentence() ,
            'description'=> fake()->paragraph() ,
            'city' => 'دمشق',
            'gender' => fake()->randomElement([Gender::MALE , Gender::FEMALE]),
            'date_of_birth' => fake()->date('Y-m-d' , Carbon::now()->subYears(18)),
            'job_role_id' => JobRole::inRandomOrder()->take(1)->first()->id ,
            'username' => fake()->unique()->randomDigit() ,
        ];
    }
}
