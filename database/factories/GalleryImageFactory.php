<?php

namespace Database\Factories;

use App\Models\Company;
use App\Constants\Disks;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GalleryImage>
 */
class GalleryImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->image('storage/app/' . Disks::COMPANY  , 500 , 500 , null , false) ,
            'company_id' => (Company::factory()->create())->id ,
        ];
    }
}
