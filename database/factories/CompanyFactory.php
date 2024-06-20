<?php

namespace Database\Factories;


use App\Models\User;
use App\Models\Company;
use App\Models\Industry;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      
        return [
            'name' => fake()->unique()->company() ,
            'profile_image_url' => null ,
            'background_image_url' => null ,
            'profile_image_id' => null ,
            'background_image_id' => null ,
            'username' => fake()->unique()->randomDigit() , 
            'description' => fake()->text(40) ,
            'size' => fake()->randomElement([10 , 100 , 1000]) ,
            'verified_at' => null ,
            'city' => 'دمشق' ,
            'region' => fake()->streetName() ,
            'street_address' => fake()->streetAddress() ,
            'industry_name' => Industry::inRandomOrder()->take(1)->first()->name  
        ];
    }
}
