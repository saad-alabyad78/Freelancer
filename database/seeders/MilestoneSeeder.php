<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Milestone;

class MilestoneSeeder extends Seeder
{
    public function run()
    {
        Milestone::create([
            'project_id' => 1,
            'description' => 'Milestone 1 Description',
            'deadline' => '2024-12-31',
            'client_ok' => false,
            'freelancer_ok' => false,
            'price' => 500,
            'finished_at' => null,
        ]);

        Milestone::create([
            'project_id' => 2,
            'description' => 'Milestone 2 Description',
            'deadline' => '2024-11-30',
            'client_ok' => true,
            'freelancer_ok' => false,
            'price' => 1000,
            'finished_at' => '2024-11-15',
        ]);
    }
}
