<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobRole;
use App\Models\Industry;
use App\Constants\Gender;
use App\Services\xmlService;
use App\Constants\LocationType;
use App\Constants\AttendenceType;
use App\Constants\Job_OfferTypes;
use App\Constants\JobOfferStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(JobOfferStatus::$types) ,
            'location_type' => fake()->randomElement(LocationType::$types) ,
            'attendence_type' => fake()->randomElement(AttendenceType::$types),
            'max_salary' => fake()->numberBetween(100 , 1000) ,
            'min_salary' => fake()->numberBetween(10 , 100) ,
            'transportation' => fake()->boolean(10) ,
            'health_insurance' => fake()->boolean(10) ,
            'military_service' => fake()->boolean(80) ,
            'max_age' => fake()->numberBetween(30 , 35) ,
            'min_age' => fake()->numberBetween(20 , 30) ,
            'gender' => fake()->randomElement([null , Gender::MALE , Gender::FEMALE]) ,
            'description' => fake()->text() ,
            'job_role_id' => JobRole::inRandomOrder()->take(1)->first()->id,
            'company_id' => Company::inRandomOrder()->take(1)->first()->id,
            'industry_name' => Industry::first()->name ,
            'military_service_required' => fake()->boolean() , 
            'age_required' => fake()->boolean() , 
            'gender_required' => fake()->boolean() , 
        ];
    }
}
