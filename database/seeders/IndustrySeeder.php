<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Industry::updateOrCreate(['name' => 'برمجي']) ;     
        Industry::updateOrCreate(['name' => 'خدمي']) ;     
    }
}
