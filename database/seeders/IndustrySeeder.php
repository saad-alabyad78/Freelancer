<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Industry::updateOrCreate(['name' => 'Information Technology']);
        Industry::updateOrCreate(['name' => 'Healthcare']);
        Industry::updateOrCreate(['name' => 'Education']);
        Industry::updateOrCreate(['name' => 'Manufacturing']);
        Industry::updateOrCreate(['name' => 'Finance']);
        Industry::updateOrCreate(['name' => 'Retail']);
        Industry::updateOrCreate(['name' => 'Real Estate']);
        Industry::updateOrCreate(['name' => 'Hospitality']);
        Industry::updateOrCreate(['name' => 'Transportation']);
        Industry::updateOrCreate(['name' => 'Construction']);
        Industry::updateOrCreate(['name' => 'Marketing & Advertising']);
        Industry::updateOrCreate(['name' => 'Telecommunications']);
        Industry::updateOrCreate(['name' => 'Entertainment']);
        Industry::updateOrCreate(['name' => 'Legal Services']);
        Industry::updateOrCreate(['name' => 'Non-Profit']);
        Industry::updateOrCreate(['name' => 'Public Administration']);
    }
}
