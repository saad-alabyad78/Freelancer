<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Project::create([
            'freelancer_id' => 1,
            'client_id' => 1,
            'client_offer_id' => 1,
            'price' => 2000,
            'days' => 30,
            'client_money' => 500,
            'client_ok' => true,
            'freelancer_ok' => false,
            'finished_at' => null,
        ]);

        Project::create([
            'freelancer_id' => 2,
            'client_id' => 2,
            'client_offer_id' => 2,
            'price' => 3000,
            'days' => 45,
            'client_money' => 1000,
            'client_ok' => false,
            'freelancer_ok' => true,
            'finished_at' => '2024-08-01',
        ]);
    }
}
