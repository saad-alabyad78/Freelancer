<?php

namespace Database\Factories;


use App\Models\User;
use App\Constants\Disks;
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
            'profile_image' => fake()->image('storage/app/' . Disks::COMPANY  ,500 , 500 , null , false  ) ,
            'background_image' => fake()->image('storage/app/' . Disks::COMPANY ,500 , 500 , null , false  ) ,
            'username' => null , 
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
