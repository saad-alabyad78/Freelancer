<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobRole;
use App\Models\Industry;
use App\Constants\Gender;
use App\Rules\GenderRule;
use App\Services\xmlService;
use App\Constants\Job_OfferTypes;
use App\Constants\Job_OfferStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class Job_OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => Job_OfferStatus::PENDING ,
            'type' => fake()->randomElement(xmlService::toJson(xmlService::read(Job_OfferTypes::xmlDatabasePath))->type) ,
            'max_salary' => fake()->numberBetween(100 , 1000) ,
            'min_salary' => fake()->numberBetween(10 , 100) ,
            'transportation' => fake()->boolean(10) ,
            'health_insurance' => fake()->boolean(10) ,
            'military_service' => fake()->boolean(80) ,
            'max_age' => fake()->numberBetween(30 , 35) ,
            'min_age' => fake()->numberBetween(20 , 30) ,
            'gender' => fake()->randomElement([null , Gender::MALE , Gender::FEMALE]) ,
            'job_role_id' => JobRole::inRandomOrder()->take(1)->get()->id,
            'company_id' => Company::inRandomOrder()->take(1)->get()->id ,
            'industry_name' => Industry::first()->name ,
        ];
    }
}
